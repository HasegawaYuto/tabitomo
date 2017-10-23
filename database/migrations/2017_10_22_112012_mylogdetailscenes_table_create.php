<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MylogdetailscenesTableCreate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mylogdetailscenes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title_id')
                  ->index();
            $table->foreign('title_id')
                  ->references('title_id')
                  ->on('mylogdetailtitles')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
            $table->string('scene_id')->unique('scene_id')->index();
            $table->string('scene')->nullable();
            $table->string('genre')->nullable();
            $table->date('theday')->nullable();
            $table->string('publish')->nullable();
            $table->double('lat')->nullable();
            $table->double('lng')->nullable();
            $table->tinyInteger('score')->unsigned()->nullable();
            $table->text('comment')->nullable();
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
        Schema::drop('mylogdetailscenes');
    }
}
