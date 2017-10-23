<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RecruitmentsCreate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recruitments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index();
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
            $table->integer('recruitment_id')->unsigned()->index();
            $table->foreign('recruitment_id')
                  ->references('id')
                  ->on('guestguides')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
            $table->timestamps();
            $table->unique(['user_id','recruitment_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('recruitments');
    }
}
