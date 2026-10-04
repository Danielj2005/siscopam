<?php
// database/migrations/xxxx_xx_xx_create_roles_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nombre'); // ej: SUPER_ADMIN, ADMIN_GYM, RECEPCIONISTA, ENTRENADOR
            $table->string('descripcion')->nullable();
            $table->jsonb('permisos_json')->nullable(); // Permisos flexibles
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};