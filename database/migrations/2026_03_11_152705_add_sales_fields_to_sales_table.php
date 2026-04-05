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
        Schema::table('sales', function (Blueprint $table) {
            $table->string('village')->nullable();
            $table->string('vehicle_no')->nullable();
            $table->decimal('rate', 10, 3)->nullable();
            $table->decimal('total', 10, 3)->nullable();
            $table->decimal('labour_charge', 10, 3)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn([
                'village',
                'vehicle_no',
                'rate',
                'total',
                'labour_charge'
            ]);
        });
    }
};
