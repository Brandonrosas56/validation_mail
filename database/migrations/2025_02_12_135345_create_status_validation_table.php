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
        Schema::create('status_validation', function (Blueprint $table) {
            $table->id();
            $table->enum('status_secop', ['Pendiente','Fallido','Rechazado','Exitoso'])->default('Pendiente');
            $table->string('message_secop')->nullable();
            $table->enum('status_email',['Pendiente','Fallido','Exitoso'])->default('Pendiente');
            $table->string('message_email')->nullable();
            $table->date('date_creation')->nullable();
            $table->date('date_validation')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('status_validation');
    }
};
