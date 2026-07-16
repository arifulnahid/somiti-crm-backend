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
        Schema::create('banks', function (Blueprint $table) {
            $table->id();

            // Owner relationship (single user)
            $table->foreignId('owner_id')
                ->constrained('users')
                ->cascadeOnDelete()
                ->index();

            // Manager relationship (multiple users)
            $table->json('manager_id')->nullable();

            // Bank details
            $table->string('bank_name');
            $table->string('bank_branch')->nullable();
            $table->string('bank_type');
            $table->string('account_number')->unique();
            $table->decimal('balance', 15, 2)->default(0);

            // Status and visibility
            $table->boolean('is_active')->default(true);
            $table->enum('visible_to', ['ADMIN', 'MANAGER', 'VOLUNTEER', 'MEMBER', 'NONE'])
                ->default('NONE');

            // Metadata
            $table->json('meta')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Additional indexes
            $table->index(['bank_type', 'is_active']);
            $table->index('bank_name');
            $table->index('account_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banks');
    }
};
