<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('asistencias', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('empresa_id')->constrained('empresas')->cascadeOnDelete();
            $table->foreignUuid('sede_id')->constrained('sedes')->cascadeOnDelete();
            $table->foreignUuid('cliente_id')->constrained('clientes')->cascadeOnDelete();

            $table->enum('metodo_validacion', ['QR_BIOMETRICO', 'CEDULA_MANUAL', 'FACE_ID', 'DISPOSITIVO_LOCAL']);
            $table->enum('estatus', ['PERMITIDO', 'DENEGADO_VENCIDO', 'DENEGADO_INACTIVO'])->default('PERMITIDO');
            $table->jsonb('detalles_dispositivo_json')->nullable(); // Guardar IP, UserAgent o Firma WebAuthn
            $table->timestamp('fecha_hora');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asistencias');
    }
};