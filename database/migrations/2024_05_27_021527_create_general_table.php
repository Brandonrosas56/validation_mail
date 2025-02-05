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
        Schema::create('general', function (Blueprint $table) {
            $table->id();
            $table->string('general_identifier', 250);
            $table->string('general_heading', 250);
            $table->string('subtitle', 250)->nullable();
            $table->string('general_catalog', 250);
            $table->string('general_admission', 250);
            $table->string('language');
            $table->string('description', 350);
            $table->string('keywords', 250);
            $table->string('coverage_name_of_the_period', 250)->nullable();
            $table->string('coverage_classification_scheme_1', 250)->nullable();
            $table->string('coverage_time', 250)->nullable();
            $table->string('coverage_classification_scheme_2', 250)->nullable();
            $table->string('coverage_classification_scheme_3', 250)->nullable();
            $table->string('coverage_continent', 250)->nullable();
            $table->string('coverage_country', 250)->nullable();
            $table->string('coverage_region', 250)->nullable();
            $table->string('coverage_state', 250)->nullable();
            $table->string('coverage_city', 250)->nullable();
            $table->string('coverage_zone', 250)->nullable();
            $table->string('coverage_address', 250)->nullable();
            $table->string('general_structure');
            $table->string('grouping_level')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('general');
    }
};
