<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBirthdaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('birthdays', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('chat_user_id');
            $table->string('name');
            $table->dateTime('birthday');
            $table->timestamps();

            $table->foreign('chat_user_id')
                ->references('id')->on('chat_users')
                ->onDelete('cascade');
            $table->unique(['chat_user_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('birthdays');
    }
}
