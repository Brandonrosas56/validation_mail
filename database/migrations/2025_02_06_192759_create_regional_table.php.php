<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegionalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('regional', function (Blueprint $table) {
            $table->bigInteger('rgn_id')->primary();
            $table->string('rgn_nombre', 50)->nullable();
            $table->string('rgn_direccion', 200)->nullable();
            $table->bigInteger('pai_id')->nullable();
            $table->string('pai_nombre', 50)->nullable();
            $table->bigInteger('dpt_id')->nullable();
            $table->string('dpt_nombre', 200)->nullable();
            $table->bigInteger('mpo_id')->nullable();
            $table->string('mpo_nombre', 200)->nullable();
            $table->bigInteger('zon_id')->nullable();
            $table->string('zon_nombre', 50)->nullable();
            $table->bigInteger('bar_id')->nullable();
            $table->string('bar_nombre', 50)->nullable();
            $table->date('rgn_fch_registro')->nullable();
            $table->smallInteger('rgn_estado')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('regional');
    }
}