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
        Schema::create('transactionlogs', function (Blueprint $table) {
            $table->id('tid');
            $table->foreignId('uid')->constrained('systemuser')->onDelete('cascade');
            $table->foreignId('rid')->constrained('route')->onDelete('cascade');
            $table->foreignId('cid')->constrained('center')->onDelete('cascade');
            $table->double('bill_amount');
            $table->double('amount');
            $table->double('difference_amount');
            $table->string('remark');
            $table->string('action');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactionlogs');
    }
};
