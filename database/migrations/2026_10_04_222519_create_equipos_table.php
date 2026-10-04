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
        // create_equipos_table.php
        Schema::create('equipos', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('empresa_id')->constrained('empresas')->cascadeOnDelete();
            $table->foreignUuid('sede_id')->constrained('sedes')->cascadeOnDelete();
            $table->string('nombre'); // Ej: "Cinta de correr #3", "Prensa 45°"
            $table->string('codigo_activo')->nullable();
            $table->enum('estado', ['OPERATIVO', 'MANTENIMIENTO', 'FUERA_SERVICIO'])->default('OPERATIVO');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipos');
    }
};
