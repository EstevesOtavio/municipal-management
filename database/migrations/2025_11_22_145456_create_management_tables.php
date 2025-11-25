<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Tabela de Secretarias (A "Mãe" de todas)
        Schema::create('secretariats', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        // 2. Tabela de Categorias (Precisa existir ANTES das ODS)
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            // Vínculo com a secretaria
            $table->foreignId('secretariat_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('color')->default('gray');
            $table->timestamps();
        });

        // 3. Tabela de Ordens de Serviço (Usa as duas acima)
        Schema::create('service_orders', function (Blueprint $table) {
            $table->id();

            $table->foreignId('secretariat_id')->constrained()->cascadeOnDelete();
            // Agora podemos vincular category_id sem erro, pois a tabela já foi criada acima
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained();

            $table->string('code')->unique();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('location_text')->nullable();

            // Removemos a coluna antiga 'category' (string)
            // Adicionamos status
            $table->string('status')->default('pending');
            $table->boolean('is_urgent')->default(false);
            $table->date('due_date')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_orders'); // Apaga o neto
        Schema::dropIfExists('categories');     // Apaga o filho
        Schema::dropIfExists('secretariats');   // Apaga o pai
    }
};
