<?php

use App\Enums\Relationship;
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
        Schema::create('nominees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->foreignId('member_id')->constrained('members')->cascadeOnDelete();
            $table->string('name');
            $table->string('fathers_name');
            $table->string('mothers_name');
            $table->enum('gender', ['male', 'female', 'none']);
            $table->date('dob');
            $table->string('phone')->nullable();
            $table->string('nid')->nullable();
            $table->string('birth_id')->nullable();
            $table->string('passport_id')->nullable();
            $table->longText('present_address')->nullable();
            $table->enum('relationship', array_column(Relationship::cases(), 'value'));
            $table->json('meta')->nullable();
            $table->timestampsTz();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nominees');
    }
};
