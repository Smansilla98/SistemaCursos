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
        Schema::table('course_user', function (Blueprint $table) {
            // Eliminar columnas no requeridas
            $table->dropColumn([
                'access_type',
                'is_unlocked',
                'unlocked_at',
                'payment_id',
                'access_key_id',
                'progress'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('course_user', function (Blueprint $table) {
            $table->string('access_type')->default('payment')->after('user_id');
            $table->boolean('is_unlocked')->default(false)->after('access_type');
            $table->timestamp('unlocked_at')->nullable()->after('is_unlocked');
            $table->foreignId('payment_id')->nullable()->constrained()->onDelete('set null')->after('unlocked_at');
            $table->foreignId('access_key_id')->nullable()->constrained()->onDelete('set null')->after('payment_id');
            $table->integer('progress')->default(0)->after('access_key_id');
        });
    }
};

