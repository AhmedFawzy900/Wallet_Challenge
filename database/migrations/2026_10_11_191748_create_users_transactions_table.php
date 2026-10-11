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
        Schema::create('users_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('transaction_reference', 255)->unique();
            $table->dateTime('transaction_date')->nullable();
            $table->decimal('amount', 15, 2);
            $table->string('currency', 3)->nullable();
            $table->string('transaction_type', 255);
            $table->string('note', 255)->nullable();
            $table->string('sender_account_number', 255);
            $table->string('reciever_account_number', 255);
            $table->string('reciever_bank_code', 255);
            $table->string('beneficiary_name', 255);
            $table->string('payment_type', 255)->nullable();
            $table->string('charge_details', 255)->nullable();

            $table->string('status', 255);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_transactions');
    }
};
