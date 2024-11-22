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
        Schema::create('labassign', function (Blueprint $table) {
            $table->id('laid');
            $table->foreignId('uid')->constrained('systemuser')->onDelete('cascade');
            $table->foreignId('lid')->constrained('lab')->onDelete('cascade');
            $table->foreignId('uid_assign')->constrained('systemuser')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('labassign');
    }
};
