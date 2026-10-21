<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bitacora_tasas_bcv', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->decimal('tasa', 10, 4)->nullable();
            $table->string('origen')->default('BCV_SCRAPING'); // 'BCV_SCRAPING', 'MANUAL_OVERRIDE'
            $table->boolean('exitoso')->default(true);
            $table->text('error_mensaje')->nullable();
            $table->timestamp('fecha_consulta');
            $table->timestamps();

            $table->index('fecha_consulta');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bitacora_tasas_bcv');
    }
};