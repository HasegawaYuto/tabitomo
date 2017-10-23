<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MylogUserCreateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mylog_user', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')
                  ->unsigned()
                  ->index();
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
            $table->string('scene_id')
                  ->index();
            $table->foreign('scene_id')
                  ->references('scene_id')
                  ->on('mylogdetailscenes')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
            $table->timestamps();
            $table->unique(['user_id','scene_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('mylog_user');
    }
}
