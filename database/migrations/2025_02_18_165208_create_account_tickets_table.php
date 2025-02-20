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
        Schema::create('account_tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id');
            $table->foreignId('type_account');
            $table->integer('ticket_id');
            $table->text('ticket_info')->nullable();
            $table->string('ticket_state')->nullable();
            $table->timestamps();
            $table->index('account_id');
        });


    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_tickets');
    }
};
