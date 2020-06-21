<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChatUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chat_users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('chat_user_id')->unique();
            $table->string('name');
            $table->text('avatar')->nullable();
            $table->string('country', 2);
            $table->string('language', 10)->nullable();
            $table->string('api_version', 10)->nullable();
            $table->string('viber_version', 10)->nullable();
            $table->boolean('eleven_a')->default(false);
            $table->softDeletes();
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
        Schema::dropIfExists('chat_users');
    }
}
