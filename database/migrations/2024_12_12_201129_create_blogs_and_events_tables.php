<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlogsAndEventsTables extends Migration
{
    public function up()
    {
        // Creating the blogs table
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('slug')->nullable();
            $table->text('content')->nullable(); // Allows long HTML content
            $table->string('image')->nullable();
            $table->tinyInteger('status')->default(0)->nullable();
            $table->timestamps();
        });

        // Creating the events table
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->time('time')->nullable();
            $table->date('date')->nullable();
            $table->string('venue')->nullable();
            $table->string('url')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        // Dropping the tables if the migration is rolled back
        Schema::dropIfExists('blogs');
        Schema::dropIfExists('events');
    }
}
