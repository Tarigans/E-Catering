<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliverySlot extends Model
{
    protected $fillable = ['day_of_week', 'starts_at', 'ends_at', 'max_orders', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }
}
