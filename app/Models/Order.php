<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_number', 'user_id', 'delivery_area_id', 'voucher_id', 'status', 'payment_method',
        'payment_gateway', 'payment_token', 'payment_url', 'recipient_name', 'recipient_phone',
        'payment_proof_path', 'payment_proof_uploaded_at', 'payment_verified_at', 'payment_rejected_at', 'payment_note',
        'delivery_address', 'event_date', 'event_time', 'delivery_date', 'delivery_time', 'subtotal', 'delivery_fee',
        'discount', 'points_redeemed', 'points_earned', 'grand_total', 'eta_minutes', 'paid_at', 'delivered_at',
    ];

    protected function casts(): array
    {
        return [
            'recipient_name' => 'encrypted',
            'recipient_phone' => 'encrypted',
            'delivery_address' => 'encrypted',
            'event_date' => 'date',
            'delivery_date' => 'date',
            'paid_at' => 'datetime',
            'delivered_at' => 'datetime',
            'payment_proof_uploaded_at' => 'datetime',
            'payment_verified_at' => 'datetime',
            'payment_rejected_at' => 'datetime',
        ];
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function pointsDiscount(): int
    {
        return intdiv($this->points_redeemed, 100) * 1000;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function area()
    {
        return $this->belongsTo(DeliveryArea::class, 'delivery_area_id');
    }

    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }
}
