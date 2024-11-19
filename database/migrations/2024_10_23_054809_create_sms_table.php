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
        Schema::create('sms', function (Blueprint $table) {
            $table->id('sid');
            $table->foreignId('tid')->constrained('transaction')->onDelete('cascade');
            $table->double('description');
            $table->string('phonenumber1');
            $table->string('phonenumber2');
            $table->string('phonenumber3');
            $table->string('phonenumber4');
            $table->string('phonenumber5');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sms');
    }
};
