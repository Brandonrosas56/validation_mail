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
        Schema::create('technical_characteristics', function (Blueprint $table) {
            $table->id();
            $table->string('technical_format', 250);
            $table->string('technical_size', 50);
            $table->string('technical_location_web', 250);
            $table->string('technical_location_source', 250);
            $table->string('technical_type', 250)->nullable();
            $table->string('technical_name', 250)->nullable();
            $table->string('minimum_technical_version', 250)->nullable();
            $table->string('maximum_technical_version', 250)->nullable();
            $table->string('technical_requirements_for_other_platforms_type', 50)->nullable();
            $table->string('technical_requirements_for_other_platforms_instruction', 50)->nullable();
            $table->string('technical_requirements_for_other_platforms_source', 100)->nullable();
            $table->string('technical_requirements_for_other_platforms_language', 50)->nullable();
            $table->string('technical_duration')->nullable();
            $table->string('use')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('technical_characteristics');
    }
};
