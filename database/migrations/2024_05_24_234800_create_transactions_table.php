<?php

use App\Enums\TransactionStatusEnum;
use App\Enums\TransactionTypeEnum;
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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('payer_account_id');
            $table->unsignedBigInteger('payee_account_id');
            $table->decimal('amount', 15);
            $table->string('description')->nullable();
            $table->enum('status', [
                TransactionStatusEnum::Pending->value,
                TransactionStatusEnum::Completed->value,
                TransactionStatusEnum::Failed->value
            ]);
            $table->enum('type', [
                TransactionTypeEnum::Transference->value
            ]);
            $table->timestamps();

            $table->foreign('payer_account_id')->references('id')->on('accounts');
            $table->foreign('payee_account_id')->references('id')->on('accounts');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
