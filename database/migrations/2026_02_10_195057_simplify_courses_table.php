<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Eliminar foreign keys primero
        Schema::table('courses', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropForeign(['teacher_id']);
        });
        
        // Eliminar columnas
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn(['category_id', 'teacher_id', 'slug', 'short_description', 'requires_payment', 'order']);
        });
        
        // Renombrar cover_image a thumbnail usando raw SQL
        DB::statement('ALTER TABLE courses CHANGE cover_image thumbnail VARCHAR(255) NULL');
        
        // Eliminar tablas no requeridas
        Schema::dropIfExists('categories');
        Schema::dropIfExists('access_keys');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recrear tablas eliminadas
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
        
        Schema::create('access_keys', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('key')->unique();
            $table->boolean('is_used')->default(false);
            $table->timestamp('used_at')->nullable();
            $table->boolean('is_single_use')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
        
        Schema::table('courses', function (Blueprint $table) {
            $table->renameColumn('thumbnail', 'cover_image');
            $table->string('slug')->unique()->after('title');
            $table->text('short_description')->nullable()->after('description');
            $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null')->after('cover_image');
            $table->foreignId('teacher_id')->nullable()->constrained('users')->onDelete('set null')->after('category_id');
            $table->boolean('requires_payment')->default(true)->after('is_active');
            $table->integer('order')->default(0)->after('requires_payment');
        });
    }
};
