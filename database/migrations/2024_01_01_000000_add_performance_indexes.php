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
        // Add indexes for products table (only if table exists)
        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                if (!Schema::hasColumn('products', 'slug') || !$this->indexExists('products', 'products_slug_index')) {
                    $table->index('slug');
                }
                if (!Schema::hasIndex('products', ['is_active', 'is_archived'])) {
                    $table->index(['is_active', 'is_archived']);
                }
                if (!Schema::hasIndex('products', ['category_id', 'is_active'])) {
                    $table->index(['category_id', 'is_active']);
                }
                if (!Schema::hasIndex('products', ['is_active', 'created_at'])) {
                    $table->index(['is_active', 'created_at']);
                }
            });
        }

        // Add indexes for orders table
        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                if (!Schema::hasIndex('orders', ['user_id', 'status'])) {
                    $table->index(['user_id', 'status']);
                }
                if (!Schema::hasIndex('orders', ['status', 'payment_status'])) {
                    $table->index(['status', 'payment_status']);
                }
                if (!Schema::hasIndex('orders', ['created_at', 'status'])) {
                    $table->index(['created_at', 'status']);
                }
            });
        }

        // Add indexes for reviews table
        if (Schema::hasTable('reviews')) {
            Schema::table('reviews', function (Blueprint $table) {
                if (!Schema::hasIndex('reviews', ['product_id', 'is_approved'])) {
                    $table->index(['product_id', 'is_approved']);
                }
                if (!Schema::hasIndex('reviews', ['user_id', 'is_approved'])) {
                    $table->index(['user_id', 'is_approved']);
                }
            });
        }

        // Add indexes for cart_items table
        if (Schema::hasTable('cart_items')) {
            Schema::table('cart_items', function (Blueprint $table) {
                if (!Schema::hasIndex('cart_items', ['cart_id', 'product_id'])) {
                    $table->index(['cart_id', 'product_id']);
                }
            });
        }

        // Add indexes for order_items table
        if (Schema::hasTable('order_items')) {
            Schema::table('order_items', function (Blueprint $table) {
                if (!Schema::hasIndex('order_items', ['order_id'])) {
                    $table->index('order_id');
                }
                if (!Schema::hasIndex('order_items', ['product_id'])) {
                    $table->index('product_id');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('products')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropIndex(['slug']);
                $table->dropIndex(['is_active', 'is_archived']);
                $table->dropIndex(['category_id', 'is_active']);
                $table->dropIndex(['is_active', 'created_at']);
            });
        }

        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->dropIndex(['user_id', 'status']);
                $table->dropIndex(['status', 'payment_status']);
                $table->dropIndex(['created_at', 'status']);
            });
        }

        if (Schema::hasTable('reviews')) {
            Schema::table('reviews', function (Blueprint $table) {
                $table->dropIndex(['product_id', 'is_approved']);
                $table->dropIndex(['user_id', 'is_approved']);
            });
        }

        if (Schema::hasTable('cart_items')) {
            Schema::table('cart_items', function (Blueprint $table) {
                $table->dropIndex(['cart_id', 'product_id']);
            });
        }

        if (Schema::hasTable('order_items')) {
            Schema::table('order_items', function (Blueprint $table) {
                $table->dropIndex(['order_id']);
                $table->dropIndex(['product_id']);
            });
        }
    }

    private function indexExists($table, $index)
    {
        return collect(DB::select("SHOW INDEX FROM {$table}"))->pluck('Key_name')->contains($index);
    }
};
