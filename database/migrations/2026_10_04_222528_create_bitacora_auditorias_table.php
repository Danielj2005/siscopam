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
        Schema::create('bitacora_auditorias', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('empresa_id')->nullable()->constrained('empresas')->cascadeOnDelete();
            $table->foreignUuid('sede_id')->nullable()->constrained('sedes')->nullOnDelete();
            $table->foreignUuid('user_id')->nullable()->constrained('users')->nullOnDelete();

            $table->string('accion'); // Ej: "PAGO_REGISTRADO", "USUARIO_ELIMINADO"
            $table->string('tabla_afectada');
            $table->uuid('registro_id')->nullable();
            $table->jsonb('datos_anteriores_json')->nullable();
            $table->jsonb('datos_nuevos_json')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bitacora_auditorias');
    }
};
