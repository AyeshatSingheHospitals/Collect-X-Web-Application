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
        Schema::create('center', function (Blueprint $table) {
            $table->id('cid');
            $table->foreignId('uid')->constrained('systemuser')->onDelete('cascade');
            $table->foreignId('rid')->constrained('route')->onDelete('cascade');
            $table->foreignId('lid')->constrained('lab')->onDelete('cascade');
            $table->string('centername');
            $table->string('authorizedperson');
            $table->string('authorizedcontact')->unique();
            $table->string('selectedcontact')->unique();
            $table->string('thirdpartycontact')->unique();
            $table->string('description');
            $table->string('latitude');
            $table->string('longitude');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('center');
    }
};
