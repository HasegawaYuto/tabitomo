<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            //
            $table->string('nickname')->nullable();
            $table->string('sex')->nullable();
            $table->date('birthday')->nullable();
            $table->string('area')->nullable();
            $table->string('snsImagePath')->nullable();
            $table->string('mime')->nullable();
            $table->binary('data')->nullable();
            $table->string('password', 60)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
