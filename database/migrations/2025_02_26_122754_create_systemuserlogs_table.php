<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('systemuserlogs', function (Blueprint $table) {
            
            $table->id('uid');  // Creates an unsignedBigInteger as primary key
            $table->unsignedBigInteger('logged_uid');

            $table->string('role');
            $table->string('fname');
            $table->string('lname');
            $table->string('contact');
            $table->string('epf');
            $table->string('username');
            $table->string('password');
            $table->string('status');
            $table->string('image');
            $table->string('action');

            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('systemuserlogs');
    }
};
