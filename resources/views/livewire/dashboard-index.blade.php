<div 
    class="min-h-screen bg-gray-100 bg-cover bg-center bg-fixed font-sans selection:bg-gray-900 selection:text-white"
    style="background-image: url('https://images.unsplash.com/photo-1480714378408-67cf0d13bc1b?ixlib=rb-4.0.3&auto=format&fit=crop&w=2000&q=80');"
>
    <div class="min-h-screen bg-white/80 overflow-y-auto pb-32"> 
        
        <div class="max-w-[1800px] mx-auto p-6 md:p-10">
            
            <div class="mb-8">
                
                <div class="mb-2">
                    <h1 class="text-4xl font-extrabold text-gray-900 tracking-tight drop-shadow-sm">
                        Secretaria de {{ $secretariat->name }}
                    </h1>
                </div>

                <div class="flex flex-col xl:flex-row justify-between items-end gap-4 py-2">
                    
                    <div class="inline-flex flex-wrap items-center bg-white border border-gray-200 rounded-lg shadow-sm p-1">
                        
                        <button 
                            wire:click="resetFilters"
                            class="flex items-center gap-2 px-3 py-2 rounded-md transition group {{ empty($activeFilters) ? 'bg-gray-100' : 'hover:bg-gray-50' }}"
                        >
                            <x-heroicon-o-document-text class="w-5 h-5 text-gray-900" />
                            <span class="text-m font-bold text-gray-600">
                                Total <span class="text-gray-900 ml-1">{{ $stats['total'] }}</span>
                            </span>
                        </button>

                        <div class="w-px h-6 bg-gray-200 mx-1"></div>

                        <button 
                            wire:click="toggleFilter('urgent')"
                            class="flex items-center gap-2 px-3 py-2 rounded-md transition {{ in_array('urgent', $activeFilters) ? 'bg-red-50 ring-1 ring-red-100' : 'hover:bg-gray-50' }}"
                        >
                            <x-heroicon-o-exclamation-circle class="w-5 h-5 text-gray-900" />
                            <span class="text-m font-bold text-red-500">
                                Urgentes <span class="ml-1">{{ $stats['urgent'] }}</span>
                            </span>
                        </button>

                        <div class="w-px h-6 bg-gray-200 mx-1"></div>

                        <button 
                            wire:click="toggleFilter('overdue')"
                            class="flex items-center gap-2 px-3 py-2 rounded-md transition {{ in_array('overdue', $activeFilters) ? 'bg-orange-50 ring-1 ring-orange-100' : 'hover:bg-gray-50' }}"
                        >
                            <x-heroicon-o-clock class="w-5 h-5 text-gray-900" />
                            <span class="text-m font-bold text-orange-500">
                                Vencidas <span class="ml-1">{{ $stats['overdue'] }}</span>
                            </span>
                        </button>

                        <div class="w-px h-6 bg-gray-200 mx-1"></div>

                        <button 
                            wire:click="toggleFilter('in_progress')"
                            class="flex items-center gap-2 px-3 py-2 rounded-md transition {{ in_array('in_progress', $activeFilters) ? 'bg-blue-50 ring-1 ring-blue-100' : 'hover:bg-gray-50' }}"
                        >
                            <x-heroicon-o-play class="w-5 h-5 text-gray-900" />
                            <span class="text-m font-bold text-blue-500">
                                Em Andamento <span class="ml-1">{{ $this->secretariat->serviceOrders()->where('status', 'in_progress')->count() }}</span>
                            </span>
                        </button>

                        <div class="w-px h-6 bg-gray-200 mx-1"></div>

                        <button 
                            wire:click="toggleFilter('done')"
                            class="flex items-center gap-2 px-3 py-2 rounded-md transition {{ in_array('done', $activeFilters) ? 'bg-green-50 ring-1 ring-green-100' : 'hover:bg-gray-50' }}"
                        >
                            <x-heroicon-o-check-circle class="w-5 h-5 text-gray-900" />
                            <span class="text-m font-bold text-green-500">
                                Concluídas <span class="ml-1">{{ $stats['done'] }}</span>
                            </span>
                        </button>
                    </div>

                    <div class="flex items-center gap-3 w-full xl:w-auto">

                        <div x-data="{ open: false }" class="relative">
                            <button 
                                @click="open = !open" 
                                @click.outside="open = false"
                                class="p-3 bg-white border border-gray-200 rounded-xl shadow-sm hover:bg-gray-50 text-gray-500 hover:text-gray-800 transition h-[46px] w-[46px] flex items-center justify-center"
                            >
                                <x-heroicon-o-cog-6-tooth class="w-6 h-6" />
                            </button>

                            <div 
                                x-show="open" 
                                x-transition 
                                class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl border border-gray-100 z-50 overflow-hidden"
                                style="display: none;"
                            >
                                <div class="p-4 border-b border-gray-100 bg-gray-50">
                                    <p class="text-xs font-bold text-gray-500 uppercase">Conta</p>
                                    <p class="text-sm font-bold text-gray-900 truncate">{{ auth()->user()->name }}</p>
                                </div>
                                <button 
                                    wire:click="$set('showSecretariatModal', true)" 
                                    @click="open = false" 
                                    class="w-full flex items-center gap-2 px-4 py-3 text-gray-700 hover:bg-gray-50 text-sm font-bold transition text-left"
                                >
                                    <x-heroicon-o-building-office-2 class="w-4 h-4" />
                                    Secretarias
                                </button>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full flex items-center gap-2 px-4 py-3 text-red-600 hover:bg-red-50 text-sm font-bold transition text-left">
                                        <x-heroicon-o-arrow-left-on-rectangle class="w-4 h-4" />
                                        Sair da Conta
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div x-data="{ open: false }" @click.outside="open = false" class="relative">
                            <button 
                                @click="open = !open" 
                                class="p-3 bg-white border border-gray-200 rounded-xl shadow-sm hover:bg-gray-50 transition relative {{ count($selectedCategories) > 0 ? 'text-gray-900 ring-2 ring-gray-900 ring-offset-2' : 'text-gray-500' }} h-[46px] w-[46px] flex items-center justify-center"
                            >
                                @if(count($selectedCategories) > 0)
                                    <x-heroicon-s-funnel class="w-6 h-6" />
                                    <span class="absolute top-2 right-2 w-2 h-2 bg-red-500 rounded-full"></span>
                                @else
                                    <x-heroicon-o-funnel class="w-6 h-6" />
                                @endif
                            </button>

                            <div 
                                x-show="open" 
                                x-transition 
                                class="absolute right-0 mt-2 w-72 bg-white rounded-xl shadow-xl border border-gray-100 z-50 p-4"
                                style="display: none;"
                            >
                                <div class="flex justify-between items-center mb-3">
                                    <h3 class="font-bold text-gray-900">Categorias</h3>
                                    <span class="text-xs text-gray-400">{{ count($categories) }} encontradas</span>
                                </div>

                                <div class="space-y-2 max-h-60 overflow-y-auto mb-4 p-1 pr-2 scrollbar-thin">
                                    @foreach($categories as $cat)
                                        <div class="flex items-center justify-between group">
                                            <label class="flex items-center gap-3 cursor-pointer flex-1">
                                                <input 
                                                    type="checkbox" 
                                                    wire:model.live="selectedCategories" 
                                                    value="{{ $cat->id }}" 
                                                    class="rounded border-gray-300 text-gray-900 focus:ring-gray-900 focus:ring-offset-0 w-4 h-4 transition duration-150 ease-in-out cursor-pointer"
                                                >
                                                <span class="text-sm font-medium text-gray-700">{{ $cat->name }}</span>
                                            </label>
                                            <button 
                                                wire:click="deleteCategory({{ $cat->id }})" 
                                                wire:confirm="Apagar categoria '{{ $cat->name }}'?"
                                                class="text-gray-300 hover:text-red-500 opacity-0 group-hover:opacity-100 transition p-1"
                                                title="Excluir Categoria"
                                            >
                                                <x-heroicon-m-trash class="w-4 h-4" />
                                            </button>
                                        </div>
                                    @endforeach
                                    @if($categories->isEmpty())
                                        <p class="text-sm text-gray-400 italic text-center py-2">Nenhuma categoria criada.</p>
                                    @endif
                                </div>

                                <div class="pt-3 border-t border-gray-100">
                                    <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Nova Categoria</label>
                                    <div class="flex gap-2">
                                        <input 
                                            type="text" 
                                            wire:model="newCategoryName" 
                                            wire:keydown.enter="createCategory"
                                            placeholder="Nome..." 
                                            class="flex-1 text-sm border-gray-200 rounded-lg focus:border-gray-900 focus:ring-0 px-2 py-1.5"
                                        >
                                        <button 
                                            wire:click="createCategory" 
                                            class="bg-gray-900 text-white p-1.5 rounded-lg hover:bg-black transition"
                                            title="Adicionar"
                                        >
                                            <x-heroicon-m-plus class="w-5 h-5" />
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="relative w-full xl:w-96 group">
                            <input 
                                wire:model.live.debounce.300ms="search" 
                                type="text" 
                                placeholder="Buscar por ODS, título..." 
                                class="w-full pl-12 pr-4 py-3 bg-white border border-gray-200 rounded-xl shadow-sm text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-0 focus:border-gray-200 transition group-hover:shadow-md"
                            >
                            <div class="absolute left-4 top-3.5 text-gray-400 group-hover:text-gray-600">
                                <x-heroicon-o-magnifying-glass class="w-5 h-5" />
                            </div>
                        </div>

                        <button wire:click="create" class="p-3 bg-gray-900 text-white rounded-xl shadow-lg shadow-gray-900/20 hover:bg-black transition transform hover:scale-105 active:scale-95 h-[46px] w-[46px] flex items-center justify-center">
                            <x-heroicon-o-plus class="w-6 h-6" />
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    
                    @foreach($orders as $order)
                        @php
                            // Estilos Padrão (Pendente)
                            $cardBg = 'bg-white border-2 border-transparent shadow-xl shadow-gray-200/50';
                            $textColor = 'text-gray-800';
                            $subTextColor = 'text-gray-500';
                            $iconColor = 'text-gray-400';
                            $badgeBg = 'bg-gray-100 text-gray-600';
                            $statusPill = 'bg-gray-100 text-gray-600';
                            $statusText = 'Pendente';

                            // Lógica de Cores Sólidas
                            if ($order->status === 'done') {
                                $cardBg = 'bg-green-500 shadow-xl shadow-green-500/20';
                                $textColor = 'text-white';
                                $subTextColor = 'text-green-50';
                                $iconColor = 'text-green-200';
                                $badgeBg = 'bg-green-700 text-white';
                                $statusPill = 'bg-white text-green-600';
                                $statusText = 'Feito';
                            } 
                            elseif ($order->is_urgent) {
                                $cardBg = 'bg-red-500 shadow-xl shadow-red-500/20';
                                $textColor = 'text-white';
                                $subTextColor = 'text-red-50';
                                $iconColor = 'text-red-200';
                                $badgeBg = 'bg-red-700 text-white';
                                $statusPill = 'bg-white text-red-600';
                                $statusText = 'Urgente';
                            }
                            elseif ($order->due_date < now()) {
                                $cardBg = 'bg-orange-500 shadow-xl shadow-orange-500/20';
                                $textColor = 'text-white';
                                $subTextColor = 'text-orange-50';
                                $iconColor = 'text-orange-200';
                                $badgeBg = 'bg-orange-700 text-white';
                                $statusPill = 'bg-white text-orange-600';
                                $statusText = 'Atrasado';
                            }
                            elseif ($order->status === 'in_progress') {
                                $cardBg = 'bg-blue-500 shadow-xl shadow-blue-500/20';
                                $textColor = 'text-white';
                                $subTextColor = 'text-blue-50';
                                $iconColor = 'text-blue-200';
                                $badgeBg = 'bg-blue-700 text-white';
                                $statusPill = 'bg-white text-blue-600';
                                $statusText = 'Em And.';
                            }
                        @endphp

                        <div 
                            wire:click="edit({{ $order->id }})" 
                            class="rounded-3xl p-6 relative cursor-pointer hover:scale-[1.02] transition-all duration-200 flex flex-col justify-between h-full min-h-[220px] {{ $cardBg }}"
                        >
                            <div class="flex justify-between items-start">
                                <div>
                                    <span class="text-[10px] font-bold uppercase tracking-widest opacity-70 {{ $textColor }}">{{ $order->code }}</span>
                                    <h3 class="font-bold text-xl leading-tight mt-1 {{ $textColor }}">{{ $order->title }}</h3>
                                </div>
                                @if($order->category)
                                    <span class="px-2 py-1 rounded-md text-[10px] font-bold uppercase tracking-wide {{ $badgeBg }}">
                                        {{ substr($order->category->name, 0, 4) }}.
                                    </span>
                                @endif
                            </div>

                            <div class="space-y-2 mt-4">
                                <div class="flex items-start gap-2 text-sm font-medium {{ $subTextColor }}">
                                    <x-heroicon-m-map-pin class="w-4 h-4 mt-0.5 shrink-0 {{ $iconColor }}" />
                                    <span class="line-clamp-2">{{ $order->location_text }}</span>
                                </div>
                                
                                @if($order->due_date)
                                    <div class="flex items-center gap-2 text-sm font-medium {{ $subTextColor }}">
                                        <x-heroicon-m-calendar class="w-4 h-4 shrink-0 {{ $iconColor }}" />
                                        <span>{{ $order->due_date->format('d/m/y') }}</span>
                                    </div>
                                @endif
                            </div>

                            <div class="flex justify-end mt-6">
                                <span class="px-4 py-1.5 rounded-full text-xs font-bold uppercase shadow-sm {{ $statusPill }}">
                                    {{ $statusText }}
                                </span>
                            </div>
                        </div>
                    @endforeach

                    <div class="mt-8">
                        {{ $orders->links() }}
                    </div>
                </div> 
            
                <div class="fixed bottom-6 left-1/2 -translate-x-1/2 z-40 max-w-[95vw]">
            
                    <div class="bg-white/90 backdrop-blur-xl border border-gray-200/50 p-2 rounded-2xl shadow-2xl flex items-center gap-2">
                        
                        @php
                            // CONFIGURAÇÃO: Quantos botões visíveis?
                            $limit = 8; 
                            
                            // Separa a lista em duas partes
                            $visibleSecs = $allSecretariats->take($limit);
                            $hiddenSecs = $allSecretariats->slice($limit);
                            
                            // Verifica se a secretaria ATUAL está escondida na lista do "..."
                            // Se estiver, precisamos destacar o botão "..."
                            $isActiveHidden = $hiddenSecs->contains('id', $secretariat->id);
                        @endphp

                        @foreach($visibleSecs as $sec)
                            <a 
                                href="{{ route('dashboard', $sec->slug) }}" 
                                class="px-4 py-3 rounded-xl text-sm font-bold whitespace-nowrap transition-all duration-200
                                {{ $sec->id === $secretariat->id 
                                    ? 'bg-gray-900 text-white shadow-lg shadow-gray-900/20 transform scale-105' 
                                    : 'bg-transparent text-gray-500 hover:bg-gray-100 hover:text-gray-900' 
                                }}"
                            >
                                {{ $sec->name }}
                            </a>
                        @endforeach
                        
                        @if($hiddenSecs->isNotEmpty())
                            <div class="relative" x-data="{ open: false }">
                                
                                <button 
                                    @click="open = !open" 
                                    @click.outside="open = false"
                                    class="px-3 py-3 rounded-xl transition-all duration-200 flex items-center justify-center
                                    {{ $isActiveHidden 
                                        ? 'bg-gray-900 text-white shadow-lg' // Fica escuro se o ativo estiver aqui dentro
                                        : 'bg-gray-50 text-gray-400 hover:bg-gray-100 hover:text-gray-600' 
                                    }}"
                                >
                                    <x-heroicon-m-ellipsis-horizontal class="w-5 h-5" />
                                </button>

                                <div 
                                    x-show="open" 
                                    x-transition:enter="transition ease-out duration-200"
                                    x-transition:enter-start="opacity-0 translate-y-2"
                                    x-transition:enter-end="opacity-100 translate-y-0"
                                    x-transition:leave="transition ease-in duration-150"
                                    x-transition:leave-start="opacity-100 translate-y-0"
                                    x-transition:leave-end="opacity-0 translate-y-2"
                                    class="absolute bottom-full left-1/2 -translate-x-1/2 mb-3 w-56 bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden"
                                    style="display: none;"
                                >
                                    <div class="max-h-60 overflow-y-auto p-2 scrollbar-thin">
                                        <div class="flex flex-col gap-1">
                                            @foreach($hiddenSecs as $sec)
                                                <a 
                                                    href="{{ route('dashboard', $sec->slug) }}" 
                                                    class="px-4 py-2 rounded-xl text-sm font-bold transition text-left block w-full truncate
                                                    {{ $sec->id === $secretariat->id 
                                                        ? 'bg-gray-100 text-gray-900' 
                                                        : 'text-gray-500 hover:bg-gray-50 hover:text-gray-900' 
                                                    }}"
                                                    title="{{ $sec->name }}"
                                                >
                                                    {{ $sec->name }}
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>

                                    <div class="absolute -bottom-1.5 left-1/2 -translate-x-1/2 w-3 h-3 bg-white border-b border-r border-gray-100 rotate-45"></div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                @if($showModal)
                    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 p-4">
                        <div class="bg-white rounded-3xl p-8 w-full max-w-lg shadow-2xl relative animate-fadeIn">
                            
                            <div class="flex justify-between items-center mb-6">
                                <h2 class="text-2xl font-extrabold text-gray-900">{{ $editingId ? 'Editar ODS' : 'Nova Solicitação' }}</h2>
                                <button wire:click="$set('showModal', false)" class="p-2 bg-gray-100 rounded-full hover:bg-gray-200 transition">
                                    <x-heroicon-o-x-mark class="w-5 h-5 text-gray-500" />
                                </button>
                            </div>

                            <div class="space-y-5">
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Título</label>
                                    <input type="text" wire:model="title" class="w-full bg-gray-50 border-transparent focus:border-gray-900 focus:bg-white focus:ring-0 rounded-xl p-3 font-medium transition" placeholder="Ex: Buraco na via">
                                    @error('title') <span class="text-red-500 text-xs font-bold">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-gray-700 mb-1">Localização</label>
                                    <div class="relative">
                                        <x-heroicon-o-map-pin class="w-5 h-5 absolute left-3 top-3.5 text-gray-400" />
                                        <input type="text" wire:model="location" class="w-full bg-gray-50 border-transparent focus:border-gray-900 focus:bg-white focus:ring-0 rounded-xl pl-10 p-3 font-medium transition" placeholder="Endereço ou Ponto de Ref.">
                                        @error('location') <span class="text-red-500 text-xs font-bold">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-1">Categoria</label>
                                        <select wire:model="category" class="w-full bg-gray-50 border-transparent focus:border-gray-900 focus:bg-white focus:ring-0 rounded-xl p-3 font-medium transition">
                                            <option value="">Selecione...</option>
                                            @foreach($categories as $cat)
                                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 mb-1">Prazo</label>
                                        <input type="date" wire:model="due_date" class="w-full bg-gray-50 border-transparent focus:border-gray-900 focus:bg-white focus:ring-0 rounded-xl p-3 font-medium transition">
                                    </div>
                                </div>

                                <div class="flex items-center justify-between bg-red-50 p-4 rounded-xl border border-red-100 cursor-pointer" wire:click="$toggle('is_urgent')">
                                    <div class="flex items-center gap-3">
                                        <div class="p-2 bg-white rounded-lg shadow-sm">
                                            <x-heroicon-s-exclamation-triangle class="w-5 h-5 text-red-500" />
                                        </div>
                                        <div>
                                            <p class="font-bold text-red-900">Marcar como Urgente</p>
                                            <p class="text-xs text-red-600">Prioridade máxima na fila</p>
                                        </div>
                                    </div>
                                    <div class="w-6 h-6 rounded-full border-2 flex items-center justify-center {{ $is_urgent ? 'bg-red-500 border-red-500' : 'border-red-200 bg-white' }}">
                                        @if($is_urgent) <x-heroicon-m-check class="w-4 h-4 text-white" /> @endif
                                    </div>
                                </div>
                                
                                @if($editingId)
                                    <div class="p-4 bg-gray-50 rounded-xl">
                                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Mudar Status</label>
                                        <div class="flex gap-2">
                                            <button wire:click="$set('status', 'pending')" class="flex-1 py-2 rounded-lg text-sm font-bold transition {{ $status === 'pending' ? 'bg-white shadow text-gray-800' : 'text-gray-400 hover:bg-gray-100' }}">Pendente</button>
                                            <button wire:click="$set('status', 'in_progress')" class="flex-1 py-2 rounded-lg text-sm font-bold transition {{ $status === 'in_progress' ? 'bg-blue-100 text-blue-700' : 'text-gray-400 hover:bg-gray-100' }}">Andamento</button>
                                            <button wire:click="$set('status', 'done')" class="flex-1 py-2 rounded-lg text-sm font-bold transition {{ $status === 'done' ? 'bg-green-100 text-green-700' : 'text-gray-400 hover:bg-gray-100' }}">Feito</button>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="flex items-center justify-between mt-8 pt-6 border-t border-gray-100">
                                <div>
                                    @if($editingId)
                                        <button wire:click="delete" wire:confirm="Excluir ODS?" class="text-red-500 font-bold text-sm hover:underline">Excluir</button>
                                    @endif
                                </div>
                                <div class="flex gap-3">
                                    <button wire:click="$set('showModal', false)" class="px-6 py-3 rounded-xl font-bold text-gray-500 hover:bg-gray-50 transition">Cancelar</button>
                                    <button wire:click="save" class="px-8 py-3 rounded-xl font-bold text-white bg-gray-900 hover:bg-black shadow-lg shadow-gray-900/20 transition transform hover:scale-105">
                                        {{ $editingId ? 'Salvar' : 'Criar' }}
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>
                @endif

                @if (session()->has('success'))
                    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show" x-transition 
                        class="fixed top-6 right-6 bg-gray-900 text-white px-6 py-4 rounded-2xl shadow-2xl z-[60] flex items-center gap-3 font-bold">
                        <x-heroicon-s-check-circle class="w-6 h-6 text-green-400" />
                        {{ session('success') }}
                    </div>
                @endif

                @if($showSecretariatModal)
                    <div class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-[80] p-4">
                        <div class="bg-white rounded-3xl p-6 w-full max-w-sm shadow-2xl relative animate-fadeIn">
                            
                            <div class="flex justify-between items-center mb-6">
                                <h2 class="text-xl font-extrabold text-gray-900">Secretarias</h2>
                                <button wire:click="$set('showSecretariatModal', false)" class="text-gray-400 hover:text-gray-600 transition">
                                    <x-heroicon-m-x-mark class="w-6 h-6" />
                                </button>
                            </div>

                            <ul class="space-y-2 mb-6 max-h-60 overflow-y-auto pr-2 scrollbar-thin">
                                @foreach($allSecretariats as $sec)
                                    <li class="flex justify-between items-center bg-gray-50 p-3 rounded-xl border border-gray-100 group">
                                        
                                        <div class="flex items-center gap-3 min-w-0 flex-1"> 
                                            <div class="p-1.5 bg-white rounded-lg shadow-sm text-gray-500 shrink-0">
                                                <x-heroicon-o-building-office class="w-4 h-4" />
                                            </div>
                                            
                                            <div class="min-w-0 flex-1">
                                                <span class="font-bold text-gray-800 block text-sm truncate" title="{{ $sec->name }}">
                                                    {{ $sec->name }}
                                                </span>
                                                <span class="text-[10px] text-gray-400 block truncate">
                                                    /{{ $sec->slug }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="pl-2 shrink-0">
                                            @if($sec->id !== $secretariat->id)
                                                <button 
                                                    wire:click="deleteSecretariat({{ $sec->id }})" 
                                                    wire:confirm="Tem certeza? Todas as ODSs dessa secretaria serão apagadas!"
                                                    class="text-gray-300 hover:text-red-500 opacity-0 group-hover:opacity-100 transition p-2"
                                                >
                                                    <x-heroicon-m-trash class="w-4 h-4" />
                                                </button>
                                            @else
                                                <span class="text-[10px] font-bold text-green-600 bg-green-100 px-2 py-0.5 rounded-full">Atual</span>
                                            @endif
                                        </div>
                                    </li>
                                @endforeach
                            </ul>

                            @error('sec_error') 
                                <div class="mb-4 p-3 bg-red-50 text-red-600 text-xs font-bold rounded-xl border border-red-100">
                                    {{ $message }}
                                </div>
                            @enderror

                            <div class="pt-4 border-t border-gray-100">
                                <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Nova Secretaria</label>
                                <div class="flex gap-2">
                                    <input 
                                        type="text" 
                                        wire:model="newSecretariatName" 
                                        wire:keydown.enter="createSecretariat"
                                        placeholder="Ex: Meio Ambiente" 
                                        class="flex-1 bg-gray-50 border-none rounded-xl p-3 text-sm focus:ring-2 focus:ring-gray-900 placeholder-gray-400 font-medium"
                                    >
                                    <button 
                                        wire:click="createSecretariat" 
                                        class="bg-gray-900 text-white p-3 rounded-xl hover:bg-black transition shadow-lg shadow-gray-900/20"
                                    >
                                        <x-heroicon-m-plus class="w-5 h-5" />
                                    </button>
                                </div>
                                @error('newSecretariatName') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>