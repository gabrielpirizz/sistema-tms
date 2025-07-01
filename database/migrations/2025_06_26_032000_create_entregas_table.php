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
        Schema::create('entregas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('transportadora_id')->constrained('transportadoras');
            $table->integer('volumes');
            $table->string('remetente_nome');
            $table->string('destinatario_nome');
            $table->string('destinatario_cpf', 11)->index();
            $table->string('destinatario_endereco');
            $table->string('destinatario_cep', length: 8);
            $table->string('destinatario_estado');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entregas');
    }
};
