<?php

use App\Enums\Occupation;
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
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->string('fathers_name');
            $table->string('mothers_name');

            $table->foreignId('permanent_address')->nullable()->constrained('addresses')->nullOnDelete();
            $table->foreignId('present_address')->nullable()->constrained('addresses')->nullOnDelete();
            $table->foreignId('branch_id')->nullable()->constrained('branches')->nullOnDelete();

            $table->jsonb('nominees')->nullable();

            $table->foreignId('society_id')->nullable();

            $table->enum('occupation', array_column(Occupation::cases(), 'value'))->default(Occupation::NONE->value)->nullable();
            $table->enum('type', ['general', 'student'])->default('general');
            $table->jsonb('meta')->nullable();
            $table->softDeletesTz();
            $table->timestampsTz();
        });

        Schema::create('balance', function (Blueprint $table){
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->foreignId('member_id')->nullable()->constrained('members')->nullOnDelete();
            $table->decimal('amount', 10, 2);
            $table->boolean('is_active');
            $table->timestampsTz();
        });

        Schema::create('credit_score', function (Blueprint $table){
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->foreignId('member_id')->nullable()->constrained('members')->nullOnDelete();
            $table->integer('scores')->default(0);
            $table->integer('green')->default(0);
            $table->integer('yellow')->default(0);
            $table->integer('red')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestampsTz();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
        Schema::dropIfExists('balance');
        Schema::dropIfExists('credit_score');
    }
};
