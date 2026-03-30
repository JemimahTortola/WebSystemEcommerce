<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotifInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notif_infos', function (Blueprint $table) {
            $table->id();
            $table->boolean('notif_new_order')->default(true);
            $table->boolean('notif_low_stock')->default(true);
            $table->boolean('notif_out_of_stock')->default(true);
            $table->boolean('notif_new_review')->default(true);
            $table->boolean('notif_new_customer')->default(false);
            $table->boolean('notif_weekly_report')->default(false);
            $table->string('notification_email')->nullable();
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
        Schema::dropIfExists('notif_infos');
    }
}
