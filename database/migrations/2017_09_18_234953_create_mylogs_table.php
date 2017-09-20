<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMylogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mylogs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')
                  ->unsigned()
                  ->index();
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
            $table->integer('title_id')->unsigned()->nullable();
            $table->tinyInteger('scene_id')->unsigned()->nullable();
            $table->tinyInteger('photo_id')->unsigned()->nullable();
            $table->string('title')->nullable();
            $table->string('scene')->nullable();
            $table->date('firstday')->nullable();
            $table->date('lastday')->nullable();
            $table->date('theday')->nullable();
            $table->string('publish')->nullable();
            $table->double('lat')->nullable();
            $table->double('lng')->nullable();
            $table->tinyInteger('score')->unsigned()->nullable();
            $table->text('comment')->nullable();
            $table->string('mime')->nullable();
            $table->binary('data')->nullable();
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
        Schema::drop('mylogs');
    }
}
