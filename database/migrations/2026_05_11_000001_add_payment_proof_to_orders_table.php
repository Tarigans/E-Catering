<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (! Schema::hasColumn('orders', 'payment_proof_path')) {
                $table->string('payment_proof_path')->nullable();
            }
            if (! Schema::hasColumn('orders', 'payment_proof_uploaded_at')) {
                $table->timestamp('payment_proof_uploaded_at')->nullable();
            }
            if (! Schema::hasColumn('orders', 'payment_verified_at')) {
                $table->timestamp('payment_verified_at')->nullable();
            }
            if (! Schema::hasColumn('orders', 'payment_rejected_at')) {
                $table->timestamp('payment_rejected_at')->nullable();
            }
            if (! Schema::hasColumn('orders', 'payment_note')) {
                $table->text('payment_note')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            foreach (['payment_note', 'payment_rejected_at', 'payment_verified_at', 'payment_proof_uploaded_at', 'payment_proof_path'] as $column) {
                if (Schema::hasColumn('orders', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
