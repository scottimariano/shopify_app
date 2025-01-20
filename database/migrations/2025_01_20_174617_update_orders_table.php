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
        Schema::table('orders', function (Blueprint $table) {
            $table->bigInteger('shopify_id')->unique();
            $table->string('status')->nullable();
            $table->string('financial_status')->nullable();
            $table->decimal('subtotal_price', 10, 2);
            $table->decimal('total_price', 10, 2);
            $table->string('currency');
            $table->string('email');
            $table->timestamp('created_at_shopify')->nullable();
            $table->timestamp('updated_at_shopify')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            //
        });
    }
};
