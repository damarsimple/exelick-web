<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->id();

            $table->string('name');
            $table->string('username');

            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');

            $table->json('socials')->nullable();
            $table->json('overlay_settings')->nullable();
            $table->json('server_metadatas')->nullable();
            $table->json('variables')->nullable();
            $table->json('banned_words')->nullable();

            $table->double('balance')->default(0);
            $table->string('tag');
            $table->string('description')->nullable();
            $table->uuid('stream_key')->unique()->nullable();
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
        Schema::dropIfExists('users');
    }
}
