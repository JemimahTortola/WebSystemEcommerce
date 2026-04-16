<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::statement('CREATE INDEX IF NOT EXISTS products_is_active_index ON products (is_active)');
        DB::statement('CREATE INDEX IF NOT EXISTS products_is_archived_index ON products (is_archived)');
        DB::statement('CREATE INDEX IF NOT EXISTS products_price_index ON products (price)');
        DB::statement('CREATE INDEX IF NOT EXISTS products_active_category_index ON products (is_active, category_id)');
        DB::statement('CREATE INDEX IF NOT EXISTS products_active_created_index ON products (is_active, created_at)');

        DB::statement('CREATE INDEX IF NOT EXISTS orders_status_index ON orders (status)');
        DB::statement('CREATE INDEX IF NOT EXISTS orders_payment_status_index ON orders (payment_status)');
        DB::statement('CREATE INDEX IF NOT EXISTS orders_created_at_index ON orders (created_at)');
        DB::statement('CREATE INDEX IF NOT EXISTS orders_user_status_index ON orders (user_id, status)');

        DB::statement('CREATE INDEX IF NOT EXISTS users_is_admin_index ON users (is_admin)');

        DB::statement('CREATE INDEX IF NOT EXISTS reviews_is_approved_index ON reviews (is_approved)');
        DB::statement('CREATE INDEX IF NOT EXISTS reviews_product_approved_index ON reviews (product_id, is_approved)');
    }

    public function down()
    {
        DB::statement('DROP INDEX IF EXISTS products_is_active_index ON products');
        DB::statement('DROP INDEX IF EXISTS products_is_archived_index ON products');
        DB::statement('DROP INDEX IF EXISTS products_price_index ON products');
        DB::statement('DROP INDEX IF EXISTS products_active_category_index ON products');
        DB::statement('DROP INDEX IF EXISTS products_active_created_index ON products');

        DB::statement('DROP INDEX IF EXISTS orders_status_index ON orders');
        DB::statement('DROP INDEX IF EXISTS orders_payment_status_index ON orders');
        DB::statement('DROP INDEX IF EXISTS orders_created_at_index ON orders');
        DB::statement('DROP INDEX IF EXISTS orders_user_status_index ON orders');

        DB::statement('DROP INDEX IF EXISTS users_is_admin_index ON users');

        DB::statement('DROP INDEX IF EXISTS reviews_is_approved_index ON reviews');
        DB::statement('DROP INDEX IF EXISTS reviews_product_approved_index ON reviews');
    }
};
