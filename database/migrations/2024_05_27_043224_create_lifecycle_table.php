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
        Schema::create('lifecycle', function (Blueprint $table) {
            $table->id();
            $table->string('life_cycle_version', 12);
            $table->string('life_cycle_status', 40);
            $table->string('role_life_cycle');
            $table->string('life_cycle_entity', 50);
            $table->date('date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lifecycle');
    }
};
