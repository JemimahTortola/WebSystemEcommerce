<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('settings')) {
            Schema::table('settings', function (Blueprint $table) {
                if (!Schema::hasColumn('settings', 'hero_image')) {
                    $table->string('hero_image')->nullable()->after('logo');
                }
                if (!Schema::hasColumn('settings', 'hero_title')) {
                    $table->string('hero_title')->nullable()->after('hero_image');
                }
                if (!Schema::hasColumn('settings', 'hero_subtitle')) {
                    $table->string('hero_subtitle')->nullable()->after('hero_title');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('settings')) {
            Schema::table('settings', function (Blueprint $table) {
                $columns = [];
                if (Schema::hasColumn('settings', 'hero_image')) $columns[] = 'hero_image';
                if (Schema::hasColumn('settings', 'hero_title')) $columns[] = 'hero_title';
                if (Schema::hasColumn('settings', 'hero_subtitle')) $columns[] = 'hero_subtitle';
                if (!empty($columns)) {
                    $table->dropColumn($columns);
                }
            });
        }
    }
};
