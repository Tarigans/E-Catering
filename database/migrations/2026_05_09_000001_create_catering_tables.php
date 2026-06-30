<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('description')->nullable();
            $table->string('icon')->default('utensils');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->unsignedInteger('price');
            $table->unsignedInteger('minimum_order')->default(1);
            $table->string('image_url')->nullable();
            $table->json('labels')->nullable();
            $table->boolean('is_popular')->default(false)->index();
            $table->boolean('is_promo')->default(false)->index();
            $table->unsignedTinyInteger('rating_average')->default(5);
            $table->unsignedInteger('rating_count')->default(0);
            $table->time('flash_sale_starts_at')->nullable();
            $table->time('flash_sale_ends_at')->nullable();
            $table->unsignedInteger('flash_sale_price')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('delivery_areas', function (Blueprint $table) {
            $table->id();
            $table->string('district');
            $table->string('village');
            $table->unsignedInteger('delivery_fee')->default(0);
            $table->unsignedSmallInteger('eta_minutes')->default(30);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->unique(['district', 'village']);
        });

        Schema::create('delivery_slots', function (Blueprint $table) {
            $table->id();
            $table->string('day_of_week');
            $table->time('starts_at');
            $table->time('ends_at');
            $table->unsignedSmallInteger('max_orders')->default(20);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('operating_hours', function (Blueprint $table) {
            $table->id();
            $table->string('day_of_week')->unique();
            $table->time('opens_at')->nullable();
            $table->time('closes_at')->nullable();
            $table->time('cutoff_at')->default('20:00:00');
            $table->boolean('is_closed')->default(false);
            $table->timestamps();
        });

        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->enum('type', ['percent', 'fixed']);
            $table->unsignedInteger('value');
            $table->unsignedInteger('minimum_spend')->default(0);
            $table->unsignedInteger('max_discount')->nullable();
            $table->dateTime('starts_at')->nullable();
            $table->dateTime('expires_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('customer_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('delivery_area_id')->constrained()->cascadeOnDelete();
            $table->string('label')->default('Rumah');
            $table->text('recipient_name');
            $table->text('phone');
            $table->text('address_line');
            $table->boolean('is_favorite')->default(false);
            $table->timestamps();
        });

        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('delivery_area_id')->constrained();
            $table->foreignId('voucher_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('status', ['waiting_payment', 'paid', 'processing', 'cooking', 'ready_to_ship', 'on_delivery', 'delivered', 'cancelled'])->default('waiting_payment')->index();
            $table->enum('payment_method', ['bank_transfer', 'qris', 'credit_card', 'cod'])->default('bank_transfer');
            $table->string('payment_gateway')->default('demo');
            $table->string('payment_token')->nullable();
            $table->string('payment_url')->nullable();
            $table->text('recipient_name');
            $table->text('recipient_phone');
            $table->text('delivery_address');
            $table->date('event_date');
            $table->time('event_time');
            $table->date('delivery_date');
            $table->time('delivery_time');
            $table->unsignedInteger('subtotal');
            $table->unsignedInteger('delivery_fee');
            $table->unsignedInteger('discount')->default(0);
            $table->unsignedInteger('points_redeemed')->default(0);
            $table->unsignedInteger('points_earned')->default(0);
            $table->unsignedInteger('grand_total');
            $table->unsignedSmallInteger('eta_minutes')->default(30);
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->foreignId('menu_item_id')->nullable()->constrained()->nullOnDelete();
            $table->string('menu_name');
            $table->unsignedInteger('price');
            $table->unsignedSmallInteger('quantity');
            $table->text('note')->nullable();
            $table->unsignedInteger('line_total');
            $table->timestamps();
        });

        Schema::create('loyalty_ledgers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
            $table->integer('points');
            $table->string('description');
            $table->timestamps();
        });

        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('menu_item_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedTinyInteger('rating');
            $table->text('comment')->nullable();
            $table->timestamps();
        });

        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('period', ['weekly', 'monthly']);
            $table->unsignedInteger('price');
            $table->unsignedTinyInteger('discount_percent')->default(0);
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscription_plans');
        Schema::dropIfExists('reviews');
        Schema::dropIfExists('loyalty_ledgers');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('customer_addresses');
        Schema::dropIfExists('vouchers');
        Schema::dropIfExists('operating_hours');
        Schema::dropIfExists('delivery_slots');
        Schema::dropIfExists('delivery_areas');
        Schema::dropIfExists('menu_items');
        Schema::dropIfExists('categories');
    }
};
