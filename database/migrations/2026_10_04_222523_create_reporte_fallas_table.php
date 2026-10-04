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
        Schema::create('reporte_fallas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('equipo_id')->constrained('equipos')->cascadeOnDelete();
            $table->foreignUuid('usuario_reporta_id')->constrained('users')->cascadeOnDelete();
            $table->text('descripcion');
            $table->enum('prioridad', ['BAJA', 'MEDIA', 'ALTA', 'CRITICA'])->default('MEDIA');
            $table->enum('estatus', ['PENDIENTE', 'EN_REPARACION', 'RESUELTO'])->default('PENDIENTE');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reporte_fallas');
    }
};
