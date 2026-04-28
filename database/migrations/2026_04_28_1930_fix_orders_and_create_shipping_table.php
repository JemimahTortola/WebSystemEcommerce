<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FixOrdersAndCreateShippingTable extends Migration
{
    public function up()
    {
        // First, fix any invalid dates in orders table
        try {
            DB::statement("UPDATE orders SET delivery_date = NULL WHERE delivery_date = '0000-00-00' OR delivery_date = ''");
        } catch (\Exception $e) {
            // Table might not have delivery_date column yet
        }

        // Add shipping columns to orders if they don't exist
        if (Schema::hasTable('orders')) {
            Schema::table('orders', function (Blueprint $table) {
                if (!Schema::hasColumn('orders', 'shipping_name')) {
                    $table->string('shipping_name')->nullable()->after('payment_status');
                }
                if (!Schema::hasColumn('orders', 'shipping_phone')) {
                    $table->string('shipping_phone', 50)->nullable()->after('shipping_name');
                }
                if (!Schema::hasColumn('orders', 'shipping_address')) {
                    $table->text('shipping_address')->nullable()->after('shipping_phone');
                }
                if (!Schema::hasColumn('orders', 'city')) {
                    $table->string('city')->nullable()->after('shipping_address');
                }
                if (!Schema::hasColumn('orders', 'postal_code')) {
                    $table->string('postal_code', 20)->nullable()->after('city');
                }
                if (!Schema::hasColumn('orders', 'delivery_date')) {
                    $table->date('delivery_date')->nullable()->after('postal_code');
                }
                if (!Schema::hasColumn('orders', 'delivery_time')) {
                    $table->string('delivery_time', 50)->nullable()->after('delivery_date');
                }
                if (!Schema::hasColumn('orders', 'delivery_notes')) {
                    $table->text('delivery_notes')->nullable()->after('delivery_time');
                }
                if (!Schema::hasColumn('orders', 'delivery_area_id')) {
                    $table->foreignId('delivery_area_id')->nullable()->after('delivery_notes')->constrained('delivery_areas')->nullOnDelete();
                }
            });
        }

        // Create shipping table
        if (!Schema::hasTable('shippings')) {
            Schema::create('shippings', function (Blueprint $table) {
                $table->id();
                $table->string('shipping_name');
                $table->string('shipping_phone', 50);
                $table->text('shipping_address');
                $table->string('city')->nullable();
                $table->string('postal_code', 20)->nullable();
                $table->date('delivery_date')->nullable();
                $table->string('delivery_time', 50);
                $table->text('delivery_notes')->nullable();
                $table->foreignId('delivery_area_id')->nullable()->constrained('delivery_areas')->nullOnDelete();
                $table->timestamps();
            });
        }

        // Add shipping_id to orders
        if (Schema::hasTable('orders') && !Schema::hasColumn('orders', 'shipping_id')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->foreignId('shipping_id')->nullable()->after('payment_status')->constrained('shippings')->nullOnDelete();
            });
        }
    }

    public function down()
    {
        // Drop shipping_id from orders
        if (Schema::hasColumn('orders', 'shipping_id')) {
            Schema::table('orders', function (Blueprint $table) {
                try {
                    $table->dropForeign(['shipping_id']);
                } catch (\Exception $e) {
                    // Foreign key might not exist
                }
                $table->dropColumn('shipping_id');
            });
        }

        // Drop shipping table
        Schema::dropIfExists('shippings');

        // Remove shipping columns from orders
        $columns = ['shipping_name', 'shipping_phone', 'shipping_address', 'city', 'postal_code', 'delivery_date', 'delivery_time', 'delivery_notes', 'delivery_area_id'];
        foreach ($columns as $col) {
            if (Schema::hasColumn('orders', $col)) {
                try {
                    Schema::table('orders', function (Blueprint $table) use ($col) {
                        $table->dropColumn($col);
                    });
                } catch (\Exception $e) {
                    // Column might have foreign keys
                }
            }
        }
    }
}
