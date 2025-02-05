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
        Schema::create('metadata', function (Blueprint $table) {
            $table->id();
            $table->string('metadata_identifier', 250);
            $table->string('meta_metadata_catalogue', 250)->nullable();
            $table->string('meta_metadata_entry', 250);
            $table->string('role_of_metadata_role', 30)->nullable();
            $table->string('role_of_metadata_contributor', 30)->nullable();
            $table->string('role_of_metadata_date', 30)->nullable();
            $table->string('role_of_metadata_email', 30)->nullable();
            $table->string('role_of_metadata_institution', 30)->nullable();
            $table->string('role_of_metadata_country', 30)->nullable();
            $table->string('meta_metadata_entity', 30)->nullable();
            $table->date('meta_metadata_date')->nullable();
            $table->string('meta_metadata_metadata_schema', 30)->nullable();
            $table->string('metadata_language')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('metadata');
    }
};
