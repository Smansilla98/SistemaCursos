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
        // Renombrar tabla payments a purchases
        Schema::rename('payments', 'purchases');
        
        // Ajustar columnas según requerimientos
        Schema::table('purchases', function (Blueprint $table) {
            // Eliminar columnas no requeridas primero
            $table->dropColumn(['payment_method', 'mercadopago_preference_id', 'payment_proof', 'notes']);
        });
        
        // Renombrar columna (hacerlo en una migración separada si es necesario)
        // Nota: renameColumn puede no funcionar en todos los DB, usar raw SQL si es necesario
        DB::statement('ALTER TABLE purchases CHANGE mercadopago_payment_id payment_id VARCHAR(255) NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchases', function (Blueprint $table) {
            $table->renameColumn('payment_id', 'mercadopago_payment_id');
            $table->string('payment_method')->default('mercadopago')->after('course_id');
            $table->string('mercadopago_preference_id')->nullable()->after('mercadopago_payment_id');
            $table->string('payment_proof')->nullable();
            $table->text('notes')->nullable();
        });
        
        Schema::rename('purchases', 'payments');
    }
};
