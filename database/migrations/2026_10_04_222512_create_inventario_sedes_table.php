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
        Schema::create('inventario_sedes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('sede_id')->constrained('sedes')->cascadeOnDelete();
            $table->foreignUuid('producto_id')->constrained('productos')->cascadeOnDelete();
            $table->integer('stock_actual')->default(0);
            $table->timestamps();

            $table->unique(['sede_id', 'producto_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventario_sedes');
    }
};
