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
        Schema::create('lablogs', function (Blueprint $table) {
            $table->id('lid');
            $table->foreignId('uid')->constrained('systemuser')->onDelete('cascade');
            $table->string('name');
            $table->string('address');
            $table->string('action');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lablogs');
    }
};
