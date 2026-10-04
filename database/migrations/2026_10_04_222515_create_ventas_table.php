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
       // create_ventas_table.php
        Schema::create('ventas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('empresa_id')->constrained('empresas')->cascadeOnDelete();
            $table->foreignUuid('sede_id')->constrained('sedes')->cascadeOnDelete();
            $table->foreignUuid('cliente_id')->nullable()->constrained('clientes')->nullOnDelete();
            $table->foreignUuid('usuario_vendedor_id')->constrained('users')->cascadeOnDelete();

            $table->decimal('total_usd', 10, 2);
            $table->decimal('total_ves', 12, 2);
            $table->decimal('tasa_bcv_usada', 10, 4);
            $table->string('metodo_pago'); // PAGO_MOVIL, EFECTIVO_USD, PUNTO, ZELLE
            $table->string('referencia')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ventas');
    }
};
