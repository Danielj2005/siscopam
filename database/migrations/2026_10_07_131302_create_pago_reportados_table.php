<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pagos_reportados', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('empresa_id')->constrained('empresas')->cascadeOnDelete();
            $table->foreignUuid('sede_id')->constrained('sedes')->cascadeOnDelete();
            $table->foreignUuid('cliente_id')->constrained('clientes')->cascadeOnDelete();

            $table->decimal('monto_usd', 10, 2);
            $table->decimal('monto_ves', 12, 2);
            $table->decimal('tasa_bcv', 10, 4);

            $table->enum('metodo_pago', ['PAGO_MOVIL', 'TRANSFERENCIA', 'EFECTIVO_USD', 'ZELLE']);
            $table->string('banco_origen')->nullable();
            $table->string('banco_destino')->nullable();
            $table->string('numero_referencia', 50);
            $table->string('comprobante_path')->nullable(); // Captura o soporte
            $table->date('fecha_pago');

            $table->integer('dias_renovacion')->default(30); // Días a extender (ej. 30 días)
            $table->enum('estatus', ['PENDIENTE', 'APROBADO', 'RECHAZADO'])->default('PENDIENTE');
            $table->string('motivo_rechazo')->nullable();

            $table->foreignUuid('aprobado_por_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('fecha_revision')->nullable();

            $table->timestamps();

            // Índices para búsquedas rápidas en panel de recepción
            $table->index(['empresa_id', 'estatus']);
            $table->index(['cliente_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pagos_reportados');
    }
};
