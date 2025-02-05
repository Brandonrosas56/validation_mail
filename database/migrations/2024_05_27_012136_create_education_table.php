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
        if (!Schema::hasTable('education')) {
            Schema::create('education', function (Blueprint $table) {
                $table->id();
                $table->string('educational_interactivity');
                $table->text('type_resources');
                $table->string('interactivity_level');
                $table->string('educational_semantic')->nullable();
                $table->string('educational_user_role')->nullable();
                $table->string('educational_context')->nullable();
                $table->string('context')->nullable();
                $table->string('educational_age_range')->nullable();
                $table->string('educational_difficulty')->nullable();
                $table->string('learning_time')->nullable();
                $table->string('educational_description');
                $table->string('educational_language');
                $table->timestamps();
            });
        }
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('education');
    }
};
