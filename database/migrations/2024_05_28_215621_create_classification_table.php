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
        Schema::create('classification', function (Blueprint $table) {
            $table->id();
            $table->string('purpose_of_classification', 250)->nullable();
            $table->string('origin_of_the_classification', 250);
            $table->string('classification_taxon', 250)->nullable();
            $table->string('classification_id', 250)->nullable();
            $table->string('classification_entry', 250)->nullable();
            $table->string('description_of_the_classification', 250)->nullable();
            $table->string('classification_keywords', 250)->nullable();
            $table->string('name_of_the_programme', 250);
            $table->string('programme_code', 250);
            $table->string('knowledge_network', 250);
            $table->string('occupational_area', 250);
            $table->string('training_center', 250);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classification');
    }
};
