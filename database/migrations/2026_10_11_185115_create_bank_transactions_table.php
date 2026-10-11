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
        Schema::create('bank_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->string('transaction_type',255);
            $table->decimal('amount', 15, 2);
            $table->string('currency', 3)->nullable();
            $table->string('note', 255)->nullable();
            $table->string('internal_reference', 255)->nullable();
            $table->dateTime('transaction_date')->nullable();
            $table->string('from_bank', 255)->nullable();
            $table->string('acount_number', 255)->nullable();
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_transactions');
    }
};
