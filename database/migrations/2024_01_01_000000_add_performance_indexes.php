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
        // Add indexes for products table
        Schema::table('products', function (Blueprint $table) {
            $table->index('slug');
            $table->index(['is_active', 'is_archived']);
            $table->index(['category_id', 'is_active']);
            $table->index(['is_active', 'created_at']);
        });

        // Add indexes for orders table
        Schema::table('orders', function (Blueprint $table) {
            $table->index('order_number');
            $table->index(['user_id', 'status']);
            $table->index(['status', 'payment_status']);
            $table->index(['created_at', 'status']);
        });

        // Add indexes for reviews table
        Schema::table('reviews', function (Blueprint $table) {
            $table->index(['product_id', 'is_approved']);
            $table->index(['user_id', 'is_approved']);
        });

        // Add indexes for cart_items table
        Schema::table('cart_items', function (Blueprint $table) {
            $table->index('cart_id');
            $table->index(['cart_id', 'product_id']);
        });

        // Add indexes for order_items table
        Schema::table('order_items', function (Blueprint $table) {
            $table->index('order_id');
            $table->index('product_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['slug']);
            $table->dropIndex(['is_active', 'is_archived']);
            $table->dropIndex(['category_id', 'is_active']);
            $table->dropIndex(['is_active', 'created_at']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['order_number']);
            $table->dropIndex(['user_id', 'status']);
            $table->dropIndex(['status', 'payment_status']);
            $table->dropIndex(['created_at', 'status']);
        });

        Schema::table('reviews', function (Blueprint $table) {
            $table->dropIndex(['product_id', 'is_approved']);
            $table->dropIndex(['user_id', 'is_approved']);
        });

        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropIndex(['cart_id']);
            $table->dropIndex(['cart_id', 'product_id']);
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropIndex(['order_id']);
            $table->dropIndex(['product_id']);
        });
    }
};
