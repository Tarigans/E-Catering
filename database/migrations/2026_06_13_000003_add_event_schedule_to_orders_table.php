<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (! Schema::hasColumn('orders', 'event_date')) {
                $table->date('event_date')->nullable()->after('delivery_address');
            }
            if (! Schema::hasColumn('orders', 'event_time')) {
                $table->time('event_time')->nullable()->after('event_date');
            }
        });

        DB::table('orders')
            ->whereNull('event_date')
            ->update([
                'event_date' => DB::raw('delivery_date'),
                'event_time' => DB::raw('delivery_time'),
            ]);
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'event_time')) {
                $table->dropColumn('event_time');
            }
            if (Schema::hasColumn('orders', 'event_date')) {
                $table->dropColumn('event_date');
            }
        });
    }
};
