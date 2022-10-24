<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGameBoardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('game_boards', function (Blueprint $table) {
            $table->id();
            $table->string('0,0')->nullable();
            $table->string('0,1')->nullable();
            $table->string('0,2')->nullable();
            $table->string('1,0')->nullable();
            $table->string('1,1')->nullable();
            $table->string('1,2')->nullable();
            $table->string('2,0')->nullable();
            $table->string('2,1')->nullable();
            $table->string('2,2')->nullable();
            $table->foreignUuid('game_id')->references('id')->on('games');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('game_boards');
    }
}
