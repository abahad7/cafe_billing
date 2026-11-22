<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Add customer_phone to orders
        Schema::table('orders', function (Blueprint $table) {
            $table->string('customer_phone')->nullable()->after('order_number');
        });

        // 2. Add stock_quantity to products
        Schema::table('products', function (Blueprint $table) {
            $table->integer('stock_quantity')->default(50)->after('price');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('customer_phone');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('stock_quantity');
        });
    }
};