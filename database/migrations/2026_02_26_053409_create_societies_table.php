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
        Schema::create('societies', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('logo')->nullable();
            $table->string('cover_image')->nullable();
            $table->longText('description');

            $table->foreignId('address')->nullable()->constrained('addresses')->nullOnDelete();

            $table->jsonb('committee')->nullable();
            $table->boolean('is_active')->default(false);
            $table->date('established_at')->nullable()->default(null);
            $table->jsonb('meta')->nullable();
            $table->softDeletesTz();
            $table->timestampsTz();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('societies');
    }
};
