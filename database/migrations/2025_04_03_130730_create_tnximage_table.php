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
        Schema::create('tnximage', function (Blueprint $table) {
            $table->id('tnxid');
            $table->foreignId('tid')->constrained('transaction')->onDelete('cascade');
            $table->string('bill_image');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tnximage');
    }
};
