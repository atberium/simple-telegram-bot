<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGuessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guesses', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('chat_id');
            $table->unsignedInteger('value')->default(0);
            $table->unsignedInteger('left')->default(config('app.value_min'));
            $table->unsignedInteger('right')->default(config('app.value_max'));
            $table->boolean('guessed')->default(false);
            $table->timestamps();
            $table->index(['chat_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('guesses');
    }
}
