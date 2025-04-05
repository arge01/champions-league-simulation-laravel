<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FinalCreateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('final', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('field');
            $table->integer('goal_field');
            $table->integer('goal_outfield');
            $table->integer('outfield');
            $table->integer('tournamed');

            $table->integer('goal_outfield_penalty')->nullable();
            $table->integer('goal_field_penalty')->nullable();
            $table->tinyInteger('is_penalty')->nullable();
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
        Schema::dropIfExists('final');
    }
}
