<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cierres_caja', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('empresa_id')->constrained('empresas')->cascadeOnDelete();
            $table->foreignUuid('sede_id')->constrained('sedes')->cascadeOnDelete();
            $table->foreignUuid('user_id')->constrained('users')->cascadeOnDelete(); // Cajero u operador

            $table->timestamp('fecha_apertura');
            $table->timestamp('fecha_cierre')->nullable();
            
            // Fondo de caja inicial
            $table->decimal('monto_inicial_usd', 10, 2)->default(0);
            $table->decimal('monto_inicial_ves', 12, 2)->default(0);

            // Totals calculados por el sistema (Ventas en POS + Pagos de membresías aprobados)
            $table->decimal('sistema_efectivo_usd', 10, 2)->default(0);
            $table->decimal('sistema_efectivo_ves', 12, 2)->default(0);
            $table->decimal('sistema_pago_movil_ves', 12, 2)->default(0);
            $table->decimal('sistema_punto_ves', 12, 2)->default(0);
            $table->decimal('sistema_zelle_usd', 10, 2)->default(0);

            // Totals declarados por el cajero al realizar el conteo físico (Arqueo)
            $table->decimal('declarado_efectivo_usd', 10, 2)->nullable();
            $table->decimal('declarado_efectivo_ves', 12, 2)->nullable();
            $table->decimal('declarado_pago_movil_ves', 12, 2)->nullable();
            $table->decimal('declarado_punto_ves', 12, 2)->nullable();
            $table->decimal('declarado_zelle_usd', 10, 2)->nullable();

            // Descuadre (Diferencia global expresada en USD según tasa del día)
            $table->decimal('diferencia_usd', 10, 2)->default(0);
            
            $table->enum('estatus', ['ABIERTA', 'CERRADA'])->default('ABIERTA');
            $table->text('observaciones')->nullable();

            $table->timestamps();

            $table->index(['sede_id', 'estatus']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cierres_caja');
    }
};