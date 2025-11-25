<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Cria seu Usuário Admin
        \App\Models\User::factory()->create([
            'name' => 'Gestor Municipal',
            'email' => 'admin@admin.com',
            'password' => bcrypt('password'),
        ]);

        // 2. Cria as Secretarias (Abas do rodapé)
        $urb = \App\Models\Secretariat::create(['name' => 'Urbanismo', 'slug' => 'urbanismo']);
        \App\Models\Secretariat::create(['name' => 'Agricultura', 'slug' => 'agricultura']);
        \App\Models\Secretariat::create(['name' => 'Saúde', 'slug' => 'saude']);

        // 3. Cria ODSs para o Urbanismo (Baseado no seu Wireframe)
        $catObras = \App\Models\Category::create(['secretariat_id' => $urb->id, 'name' => 'Obras', 'color' => 'blue']);
        $catLuz = \App\Models\Category::create(['secretariat_id' => $urb->id, 'name' => 'Iluminação', 'color' => 'yellow']);
        $catLimp = \App\Models\Category::create(['secretariat_id' => $urb->id, 'name' => 'Limpeza', 'color' => 'green']);

        // Caso 1: Urgente (UG)
        \App\Models\ServiceOrder::create([
            'secretariat_id' => $urb->id,
            'code' => 'ODS-001',
            'title' => 'Buraco na Av. Central',
            'location_text' => 'Av. Central, próx. Banco',
            'category_id' => $catObras->id,
            'status' => 'pending',
            'is_urgent' => true,
            'due_date' => now()->addDays(5),
        ]);

        // Caso 2: Vencida (V)
        \App\Models\ServiceOrder::create([
            'secretariat_id' => $urb->id,
            'code' => 'ODS-002',
            'title' => 'Troca de Lâmpada',
            'location_text' => 'Praça da Matriz',
            'category_id' => $catObras->id,
            'status' => 'pending',
            'is_urgent' => false,
            'due_date' => now()->subDays(2), // Data no passado
        ]);

        // Caso 3: Em Andamento (EA)
        \App\Models\ServiceOrder::create([
            'secretariat_id' => $urb->id,
            'code' => 'ODS-003',
            'title' => 'Poda de Árvore',
            'location_text' => 'Rua das Flores, 10',
            'category_id' => $catObras->id,
            'status' => 'in_progress',
            'due_date' => now()->addDays(10),
        ]);

        // Caso 4: Concluída
        \App\Models\ServiceOrder::create([
            'secretariat_id' => $urb->id,
            'code' => 'ODS-004',
            'title' => 'Limpeza de Bueiro',
            'location_text' => 'Rua 5, Bairro Novo',
            'category_id' => $catObras->id,
            'status' => 'done',
            'due_date' => now()->subDays(5),
        ]);
    }
}
