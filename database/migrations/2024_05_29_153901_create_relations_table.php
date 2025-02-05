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
        Schema::create('relations', function (Blueprint $table) {
            $table->id();
            $table->string('type_of_relationship', 250)->nullable();
            $table->string('relationship_identifier', 250)->nullable();
            $table->string('description_of_relations', 250)->nullable();
            $table->string('relationship_catalogue', 250)->nullable();
            $table->string('relationship_entry', 250)->nullable();
            $table->timestamps();
        });

        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('relations');
    }
};
