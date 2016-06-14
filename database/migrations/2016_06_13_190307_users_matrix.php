<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UsersMatrix extends Migration
{
    public function up()
    {
        Schema::create('users_matrix', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('userId');
            $table->integer('videoId');
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
        Schema::drop('users_matrix');
    }
}
