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
        // Renombrar tabla modules a lessons
        Schema::rename('modules', 'lessons');
        
        // Agregar campos de archivo a lessons
        Schema::table('lessons', function (Blueprint $table) {
            $table->string('file_type')->nullable()->after('description'); // video | pdf
            $table->string('file_path')->nullable()->after('file_type');
            $table->boolean('is_locked')->default(true)->after('file_path');
            $table->dropColumn('is_active'); // No requerido
        });
        
        // Migrar datos de course_files a lessons si existen
        // Esto se hace en caso de que haya datos existentes
        if (Schema::hasTable('course_files')) {
            DB::statement("
                UPDATE lessons l
                INNER JOIN course_files cf ON cf.module_id = l.id
                SET l.file_type = cf.file_type,
                    l.file_path = cf.file_path,
                    l.is_locked = cf.is_locked
                WHERE cf.module_id IS NOT NULL
            ");
        }
        
        // Eliminar tabla course_files (ya no se necesita)
        Schema::dropIfExists('course_files');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lessons', function (Blueprint $table) {
            $table->dropColumn(['file_type', 'file_path', 'is_locked']);
            $table->boolean('is_active')->default(true);
        });
        
        Schema::rename('lessons', 'modules');
        
        // Recrear course_files si es necesario
        Schema::create('course_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->foreignId('module_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('file_path');
            $table->string('file_type');
            $table->string('mime_type')->nullable();
            $table->integer('file_size')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_locked')->default(true);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }
};
