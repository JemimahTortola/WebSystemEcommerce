<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shipping_trackings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->string('status', 50);
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('location', 255)->nullable();
            $table->boolean('is_public')->default(true);
            $table->timestamps();
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->string('tracking_number', 100)->nullable()->after('payment_status');
            $table->string('courier', 100)->nullable()->after('tracking_number');
            $table->string('estimated_delivery')->nullable()->after('courier');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['tracking_number', 'courier', 'estimated_delivery']);
        });
        
        Schema::dropIfExists('shipping_trackings');
    }
};
