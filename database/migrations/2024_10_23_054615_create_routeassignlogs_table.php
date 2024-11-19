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
        Schema::create('routeassignlogs', function (Blueprint $table) {
            $table->id('raid');
            $table->foreignId('uid')->constrained('systemuser')->onDelete('cascade');
            $table->foreignId('rid')->constrained('route')->onDelete('cascade');
            $table->foreignId('uid_ro')->constrained('systemuser')->onDelete('cascade');
            $table->string('action');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('routeassignlogs');
    }
};
