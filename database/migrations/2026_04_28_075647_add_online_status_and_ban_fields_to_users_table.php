<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOnlineStatusAndBanFieldsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('is_online')->default(false)->after('password');
            $table->timestamp('last_activity_at')->nullable()->after('is_online');
            $table->timestamp('banned_until')->nullable()->after('last_activity_at');
            $table->text('ban_reason')->nullable()->after('banned_until');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['is_online', 'last_activity_at', 'banned_until', 'ban_reason']);
        });
    }
}
