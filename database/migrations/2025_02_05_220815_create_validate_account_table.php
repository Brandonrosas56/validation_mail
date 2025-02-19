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
            $table->foreignId('rgn_id');
            $table->string('primer_nombre');
            $table->string('segundo_nombre')->nullable();
            $table->string('primer_apellido');
            $table->string('segundo_apellido');
            $table->string('documento_proveedor');
            $table->string('tipo_documento');
            $table->string('correo_personal');
            $table->string('correo_institucional');
            $table->string('numero_contrato');
            $table->date('fecha_inicio_contrato');
            $table->date('fecha_terminacion_contrato');
            $table->string('usuario');
            $table->string('rol_asignado');
            $table->string('estado')->nullable();
            $table->integer('intentos_validacion')->default(4)->nullable();
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
