<?php
// database/migrations/xxxx_xx_xx_create_historial_pagos_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('historial_pagos', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('empresa_id')->constrained('empresas')->cascadeOnDelete();
            $table->foreignUuid('sede_id')->constrained('sedes')->cascadeOnDelete();
            $table->foreignUuid('cliente_id')->constrained('clientes')->cascadeOnDelete();
            $table->foreignUuid('plan_id')->nullable()->constrained('planes')->nullOnDelete();
            $table->foreignUuid('usuario_registro_id')->nullable()->constrained('users')->nullOnDelete();

            // Módulo Multimoneda
            $table->decimal('monto_usd', 10, 2);
            $table->decimal('monto_ves', 12, 2);
            $table->decimal('tasa_bcv_usada', 10, 4);
            $table->string('metodo_pago'); // PAGO_MOVIL, EFECTIVO_USD, EFECTIVO_VES, ZELLE, PUNTO
            $table->string('referencia')->nullable();
            $table->string('banco_origen')->nullable();
            $table->string('banco_destino')->nullable();
            $table->enum('estatus', ['COMPLETADO', 'PENDIENTE', 'RECHAZADO'])->default('COMPLETADO');
            $table->text('notas')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('historial_pagos');
    }
};