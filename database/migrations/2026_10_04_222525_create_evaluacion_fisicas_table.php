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
        Schema::create('evaluaciones_fisicas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('empresa_id')->constrained('empresas')->cascadeOnDelete();
            $table->foreignUuid('cliente_id')->constrained('clientes')->cascadeOnDelete();
            $table->foreignUuid('evaluador_id')->constrained('users')->cascadeOnDelete();

            $table->date('fecha');
            $table->decimal('peso_kg', 5, 2);
            $table->decimal('estatura_cm', 5, 2);
            $table->decimal('porcentaje_grasa', 5, 2)->nullable();
            $table->decimal('porcentaje_musculo', 5, 2)->nullable();
            $table->jsonb('medidas_json')->nullable(); // {"cintura": 80, "pecho": 100, "brazo": 38}
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluacion_fisicas');
    }
};
