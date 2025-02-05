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
        Schema::create('version_controls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->text('name_file');
            $table->text('new_name');
            $table->string('type', 191);
            $table->text('route');
            $table->integer('old_version');
            $table->integer('new_version');
            $table->enum('action', ['Actualizado', 'Elimnado', 'Restaurado']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('version_controls');
    }
};
