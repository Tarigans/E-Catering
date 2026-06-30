<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    protected $fillable = ['code', 'name', 'type', 'value', 'minimum_spend', 'max_discount', 'starts_at', 'expires_at', 'is_active'];

    protected function casts(): array
    {
        return ['starts_at' => 'datetime', 'expires_at' => 'datetime', 'is_active' => 'boolean'];
    }

    public function discountFor(int $subtotal): int
    {
        if (! $this->is_active || $subtotal < $this->minimum_spend) {
            return 0;
        }

        $discount = $this->type === 'percent'
            ? (int) floor($subtotal * ($this->value / 100))
            : (int) $this->value;

        return $this->max_discount ? min($discount, $this->max_discount) : $discount;
    }
}
