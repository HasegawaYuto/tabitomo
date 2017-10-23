<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MylogdetateiltitlesTableCreate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mylogdetailtitles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')
                  ->unsigned()
                  ->index();
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
            $table->string('title_id')->unique()->index();
            $table->string('title')->nullable();
            $table->date('firstday')->nullable();
            $table->date('lastday')->nullable();
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
        Schema::drop('mylogdetailtitles');
    }
}
