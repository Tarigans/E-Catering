<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('menu_items', function (Blueprint $table) {
            if (! Schema::hasColumn('menu_items', 'minimum_order')) {
                $table->unsignedInteger('minimum_order')->default(1)->after('price');
            }
        });

        if (Schema::hasColumn('menu_items', 'stock')) {
            DB::table('menu_items')->update(['minimum_order' => 10]);

            Schema::table('menu_items', function (Blueprint $table) {
                $table->dropColumn('stock');
            });
        }
    }

    public function down(): void
    {
        Schema::table('menu_items', function (Blueprint $table) {
            if (! Schema::hasColumn('menu_items', 'stock')) {
                $table->unsignedInteger('stock')->default(0)->after('price');
            }
        });

        if (Schema::hasColumn('menu_items', 'minimum_order')) {
            Schema::table('menu_items', function (Blueprint $table) {
                $table->dropColumn('minimum_order');
            });
        }
    }
};
