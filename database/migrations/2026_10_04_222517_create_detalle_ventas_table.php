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
        // create_detalle_ventas_table.php
        Schema::create('detalle_ventas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('venta_id')->constrained('ventas')->cascadeOnDelete();
            $table->foreignUuid('producto_id')->constrained('productos')->cascadeOnDelete();
            $table->integer('cantidad');
            $table->decimal('precio_unitario_usd', 10, 2);
            $table->decimal('subtotal_usd', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detalle_ventas');
    }
};
