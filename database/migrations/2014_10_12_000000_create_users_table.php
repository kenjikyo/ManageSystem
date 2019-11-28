<?php

use Illuminate\Support\Facades\Schema;
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
            $table->increments('User_ID');
            $table->string('User_Name');
            $table->string('User_Email')->unique();
            $table->string('User_Password');
            $table->dateTime('User_RegisteredDatetime');
            $table->tinyInteger('User_Status');
            $table->tinyInteger('User_Level');
            $table->integer('User_Parent');
            $table->string('User_Tree');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
