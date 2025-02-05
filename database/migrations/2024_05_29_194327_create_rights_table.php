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
        Schema::create('rights', function (Blueprint $table) {
            $table->id();
            $table->string('contributors_contributors', 30);
            $table->string('contributors_role', 30);
            $table->string('contributors_date', 30);
            $table->string('contributors_type', 30);
            $table->string('contributors_contact', 30);
            $table->string('contributors_identifier', 30);
            $table->string('contributors_country_of_origin', 30);
            $table->string('contributors_institution', 30);
            $table->string('cost_of_fees')->nullable();
            $table->string('copyright_and_other_restrictions');
            $table->string('description_of_rights', 250);
            $table->string('right_of_appeal');
            $table->string('availability');
            $table->timestamps();
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rights');
    }
};
