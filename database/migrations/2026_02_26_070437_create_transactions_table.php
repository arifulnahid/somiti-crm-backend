<?php

use App\Enums\TransactionStatus;
use App\Enums\TransactionType;
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
            $table->uuid('transaction_id');
            $table->string('title');

            $table->decimal('amount', 15, 2);
            $table->decimal('current_balance', 15, 2);

            $table->enum('type', array_column(TransactionType::cases(), 'value'));
            $table->enum('status', array_column(TransactionStatus::cases(), 'value'))->default(TransactionStatus::PENDING);
            $table->boolean('success')->default(false);

            $table->foreignId('sender_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('receiver_id')->nullable()->constrained('users')->nullOnDelete();

            $table->longText('description')->nullable();
            $table->json('meta')->nullable();

            $table->softDeletesTz();
            $table->timestampsTz();

            $table->index('sender_id');
            $table->index('receiver_id');
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
