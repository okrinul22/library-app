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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')->nullable()->constrained('transactions')->onDelete('set null');
            $table->decimal('amount', 10, 2);
            $table->enum('payment_method', ['bank_transfer', 'credit_card', 'e_wallet', 'cash'])->default('bank_transfer');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->string('proof')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->date('payment_date')->nullable();
            $table->timestamps();

            $table->index('transaction_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
