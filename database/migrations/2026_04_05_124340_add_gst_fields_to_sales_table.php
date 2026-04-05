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
            $table->enum('tax_type', ['none', 'igst', 'cgst_sgst'])->default('none')->after('total');
            $table->decimal('gst_rate', 5, 2)->default(0)->after('tax_type');
            $table->decimal('taxable_amount', 12, 2)->default(0)->after('gst_rate');
            $table->decimal('cgst_amount', 12, 2)->default(0)->after('taxable_amount');
            $table->decimal('sgst_amount', 12, 2)->default(0)->after('cgst_amount');
            $table->decimal('igst_amount', 12, 2)->default(0)->after('sgst_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn([
                'tax_type',
                'gst_rate',
                'taxable_amount',
                'cgst_amount',
                'sgst_amount',
                'igst_amount'
            ]);
        });
    }
};
