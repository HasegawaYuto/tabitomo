<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('email')->unique();
            $table->string('password', 60)->nullable();
            $table->string('nickname')->nullable();
            $table->string('sex')->nullable();
            $table->date('birthday')->nullable();
            $table->string('area')->nullable();
            $table->string('snsImagePath')->nullable();
            $table->string('mime')->nullable();
            $table->binary('data')->nullable();
            $table->integer('facebook_id')->unsigned()->nullable();
            $table->integer('twitter_id')->unsigned()->nullable();
            $table->integer('google_id')->unsigned()->nullable();
            $table->integer('line_id')->unsigned()->nullable();
            $table->integer('yahoo_id')->unsigned()->nullable();
            $table->rememberToken();
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
        Schema::drop('users');
    }
}
