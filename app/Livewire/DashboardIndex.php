<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Secretariat;
use App\Models\ServiceOrder; // Importamos o Model da ODS
use App\Models\Category;
use Livewire\WithPagination;
use Illuminate\Support\Str; // Para gerar o código aleatório

class DashboardIndex extends Component
{
    use WithPagination;

    public Secretariat $secretariat;
    public $showSecretariatModal = false;
    public $newSecretariatName = '';
    public $activeFilters = [];
    public $selectedCategories = [];
    // --- NOVO: Variáveis do Modal (Estado) ---
    public $showModal = false; // Começa fechado
    // Campos do Formulário (Data Binding)
    public $title = '';
    public $location = '';
    public $category = ''; // Valor padrão
    public $is_urgent = false;
    public $due_date = '';
    public $editingId = null; // Guarda o ID se estivermos editando
    public $status = 'pending'; // Agora podemos mudar o status no modal
    public $showCategoryModal = false;
    public $newCategoryName = '';
    public $search = '';

    public function mount(Secretariat $secretariat)
    {
        $this->secretariat = $secretariat;
    }

    public function createSecretariat()
    {
        $this->validate(['newSecretariatName' => 'required|min:3|unique:secretariats,name']);

        // Cria gerando o slug (Ex: "Meio Ambiente" -> "meio-ambiente")
        \App\Models\Secretariat::create([
            'name' => $this->newSecretariatName,
            'slug' => Str::slug($this->newSecretariatName)
        ]);

        $this->newSecretariatName = ''; // Limpa input
        // O Livewire atualiza a lista $allSecretariats automaticamente no render
        session()->flash('success', 'Secretaria criada!');
    }

    public function deleteSecretariat($id)
    {
        // Trava de Segurança: Não pode deletar a secretaria que está aberta na tela
        if ($id === $this->secretariat->id) {
            $this->addError('sec_error', 'Você não pode excluir a secretaria atual enquanto navega nela.');
            return;
        }

        \App\Models\Secretariat::destroy($id);
    }

    public function manageCategories()
    {
        $this->showCategoryModal = true;
    }

    public function createCategory()
    {
        $this->validate(['newCategoryName' => 'required|min:3']);

        $this->secretariat->categories()->create([
            'name' => $this->newCategoryName,
            'color' => 'gray'
        ]);

        $this->newCategoryName = ''; // Limpa o input
    }

    public function deleteCategory($id)
    {
        // Se a categoria deletada estiver selecionada no filtro, remove ela do array
        if (($key = array_search($id, $this->selectedCategories)) !== false) {
            unset($this->selectedCategories[$key]);
        }
        
        \App\Models\Category::destroy($id);
    }

    // --- NOVO: Abrir o Modal ---
    public function create()
    {
        $this->reset(['title', 'location', 'category', 'is_urgent', 'due_date', 'editingId', 'status']);
        $this->showModal = true;
    }

    public function edit($id)
    {
        $order = ServiceOrder::findOrFail($id);

        // Preenche o formulário com os dados do banco
        $this->editingId = $id;
        $this->title = $order->title;
        $this->location = $order->location_text;
        $this->category = $order->category_id;
        $this->is_urgent = $order->is_urgent;
        $this->status = $order->status; // Importante
        // Formata a data para o input HTML (Y-m-d)
        $this->due_date = $order->due_date?->format('Y-m-d');

        $this->showModal = true;
    }

    // --- NOVO: Salvar no Banco ---
    public function save()
    {
        if ($this->category === ""){
            $this->category = null;
        }

        $this->validate([
            'title' => 'required|min:3',
            'location' => 'required',
            'due_date' => 'nullable|date',
            'category' => 'nullable|integer',
        ]);

        $data = [
            'secretariat_id' => $this->secretariat->id,
            'title' => $this->title,
            'location_text' => $this->location,
            'category_id' => $this->category,
            'is_urgent' => $this->is_urgent,
            'due_date' => empty($this->due_date) ? null : $this->due_date,
            'status' => $this->status, // Salva o status novo
        ];

        if ($this->editingId) {
            // MODO EDIÇÃO: Atualiza o existente
            $order = ServiceOrder::find($this->editingId);
            $order->update($data);
        } else {
            // MODO CRIAÇÃO: Cria um novo
            $data['user_id'] = auth()->id();
            $nextId = ServiceOrder::withTrashed()->max('id') + 1;
            $data['code'] = 'ODS-' . str_pad($nextId, 3, '0', STR_PAD_LEFT);
            ServiceOrder::create($data);
        }

        $this->showModal = false;
        session()->flash('success', 'Operação realizada com sucesso!');
    }

    public function delete()
    {
        // Só deleta se estiver editando algo existente
        if ($this->editingId) {
            $order = ServiceOrder::find($this->editingId);
            if ($order) {
                $order->delete(); // Soft delete (vai pra lixeira, não apaga do banco pra sempre)
            }
        }

        // Fecha o modal e limpa tudo
        $this->showModal = false;
        $this->reset(['editingId', 'title', 'location', 'category', 'is_urgent', 'due_date', 'status']);
        session()->flash('success', 'Ordem de serviço removida.');
    }

    public function resetFilters()
    {
        $this->activeFilters = []; // Limpa o array
        $this->resetPage();
    }

    public function toggleFilter($filter)
    {
        if (in_array($filter, $this->activeFilters)) {
            // Se já tem, remove (desmarca)
            $this->activeFilters = array_diff($this->activeFilters, [$filter]);
        } else {
            // Se não tem, adiciona (marca)
            $this->activeFilters[] = $filter;
        }
        $this->resetPage();
    }

    public function toggleCategory($category)
    {
        // Se já estiver clicada, desmarca (vira null). Se não, marca.
        $this->selectedCategory = ($this->selectedCategory === $category) ? null : $category;
        $this->resetPage(); // Volta pra página 1
    }

    public function render()
    {
        $query = $this->secretariat->serviceOrders()->orderBy('due_date', 'asc');

        $query->when($this->search, function($q) {
            $q->where(function($sub) {
                $sub->where('title', 'ilike', '%' . $this->search . '%')
                    ->orWhere('code', 'ilike', '%' . $this->search . '%');
            });
        });

        if (in_array('urgent', $this->activeFilters)) {
            $query->where('is_urgent', true);
        }

        if (in_array('overdue', $this->activeFilters)) {
            $query->where('due_date', '<', now())->where('status', '!=', 'done');
        }

        // Para Status (Pendente/Andamento), se ambos estiverem marcados, 
        // queremos mostrar (Pendente OU Andamento).
        $statusFilters = array_intersect(['pending', 'in_progress', 'done'], $this->activeFilters);
        
        if (!empty($statusFilters)) {
            $query->whereIn('status', $statusFilters);
        }

        // 3. APLICA O FILTRO DE CATEGORIA
        if (!empty($this->selectedCategories)) {
            $query->whereIn('category_id', $this->selectedCategories);
        }

        $stats = [
        'total' => $this->secretariat->serviceOrders()->count(),
        'urgent' => $this->secretariat->serviceOrders()->where('is_urgent', true)->where('status', '!=', 'done')->count(),
        'overdue' => $this->secretariat->serviceOrders()->where('due_date', '<', now())->where('status', '!=', 'done')->count(),
        'done' => $this->secretariat->serviceOrders()->where('status', 'done')->count(),
        ];

        return view('livewire.dashboard-index', [
            'orders' => $query->paginate(12),
            'allSecretariats' => \App\Models\Secretariat::all(),
            'categories' => $this->secretariat->categories,
            'stats' => $stats,
        ])->layout('layouts.app');
    }
}
