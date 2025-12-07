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
        Schema::create('daily_productions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->decimal('production_qty', 12, 3)->default(0); // e.g., kg
            $table->date('date');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('note')->nullable();
            $table->timestamps();

            $table->unique(['product_id','date','user_id'], 'prod_date_user_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_productions');
    }
};
