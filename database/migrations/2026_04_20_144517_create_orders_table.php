<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('order_number')->unique();
            $table->decimal('total_amount', 10, 2);
            $table->string('status', 50);
            $table->string('payment_status', 50);
            $table->string('shipping_name');
            $table->string('shipping_phone', 50);
            $table->text('shipping_address');
            $table->date('delivery_date');
            $table->string('delivery_time', 50);
            $table->text('delivery_notes')->nullable();
            $table->text('gift_message')->nullable();
            $table->unsignedBigInteger('coupon_id')->nullable();
            $table->string('tracking_number', 100)->nullable();
            $table->string('courier', 100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
