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
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('address_id')->nullable()->constrained('addresses')->nullOnDelete();
            $table->enum('type', [ 'central', 'divison', 'district', 'upazila', 'union', 'ward'])->comment('Union, Upzilla, Zilla, Division, Center');

            $table->foreignId('manager')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('cashier')->nullable()->constrained('users')->nullOnDelete();

            $table->foreignId('parent_branch')->nullable()->constrained('branches')->nullOnDelete();

            $table->boolean('is_active')->default(true);

            $table->softDeletesTz();
            $table->jsonb('meta')->nullable();
            $table->timestampsTz();
        });


        Schema::create('branch_account', function (Blueprint $table){
            $table->id();
            $table->foreignId('address_id')->constrained('addresses')->onDelete('cascade');
            $table->decimal('cash', 10, 2)->default(0);
            $table->decimal('ewallet', 10, 2)->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestampsTz();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branches');
        Schema::dropIfExists('branch_account');
    }
};
