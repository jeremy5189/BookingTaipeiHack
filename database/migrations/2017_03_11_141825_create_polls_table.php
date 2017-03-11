<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePollsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('polls', function (Blueprint $table) {
            $table->string('id')->unique(); // Hash
            $table->string('title');
            $table->date('startDate')->nullable();
            $table->date('endDate')->nullable();
            $table->integer('personAmount');
            $table->integer('author');
            //$table->foreign('author')->references('id')->on('users');
            $table->string('hotel');
            //$table->foreign('hotel')->references('id')->on('hotels');
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
        Schema::dropIfExists('polls');
    }
}
