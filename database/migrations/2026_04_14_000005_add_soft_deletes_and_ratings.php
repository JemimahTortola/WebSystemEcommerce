<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->softDeletes();
            $table->decimal('average_rating', 3, 2)->default(0)->after('is_archived');
            $table->integer('review_count')->default(0)->after('average_rating');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('product_variants', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('product_images', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropColumn(['average_rating', 'review_count']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::table('product_images', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
