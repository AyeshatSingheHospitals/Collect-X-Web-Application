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
        Schema::create('routeassign', function (Blueprint $table) {
            $table->id('raid');
            $table->foreignId('uid')->constrained('systemuser')->onDelete('cascade'); // add onDelete as needed
            $table->foreignId('uid_ro')->constrained('systemuser')->onDelete('cascade');
            $table->foreignId('rid')->constrained('route')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('routeassign');
    }
};

