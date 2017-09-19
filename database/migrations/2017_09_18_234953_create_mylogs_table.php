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
            $table->string('title_id')->nullable();
            $table->string('title')->nullable();
            $table->string('firstday')->nullable();
            $table->string('lastday')->nullable();
            $table->string('scene_id')->nullable();
            $table->string('scene')->nullable();
            $table->string('publish')->nullable();
            $table->string('theday')->nullable();
            $table->integer('score')->nullable();
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
