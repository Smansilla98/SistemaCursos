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
        Schema::create('course_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('access_type')->default('payment'); // payment, key, manual
            $table->boolean('is_unlocked')->default(false);
            $table->timestamp('unlocked_at')->nullable();
            $table->foreignId('payment_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('access_key_id')->nullable()->constrained()->onDelete('set null');
            $table->integer('progress')->default(0); // porcentaje de progreso
            $table->timestamps();
            $table->unique(['course_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_user');
    }
};
