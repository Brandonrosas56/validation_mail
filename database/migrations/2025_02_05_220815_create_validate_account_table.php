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
        Schema::create('validate_account', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rgn_id')->nullable();
            $table->string('primer_nombre');
            $table->string('segundo_nombre')->nullable();
            $table->string('primer_apellido');
            $table->string('segundo_apellido')->nullable();
            $table->string('documento_proveedor')->nullable();
            $table->string('correo_personal')->unique();
            $table->string('correo_institucional')->unique();
            $table->string('numero_contrato');
            $table->date('fecha_inicio_contrato');
            $table->date('fecha_terminacion_contrato');
            $table->string('usuario')->unique();
            $table->string('estado')->nullable();
            $table->integer('intentos_validacion')->default(4)->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('validate_account');
    }
};
