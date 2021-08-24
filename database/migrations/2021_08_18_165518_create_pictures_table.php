<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePicturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pictures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('mime');
            $table->unsignedBigInteger('original_size')->default(0);
            $table->unsignedBigInteger('compressed_size')->default(0);
            $table->string('cid')->nullable();
            $table->string('path')->nullable();
            $table->string('roles')->nullable();
            $table->unsignedBigInteger('attachable_id')->nullable();
            $table->string('attachable_type')->nullable();
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
        Schema::dropIfExists('pictures');
    }
}
