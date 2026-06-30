<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerAddress extends Model
{
    protected $fillable = ['user_id', 'delivery_area_id', 'label', 'recipient_name', 'phone', 'address_line', 'is_favorite'];

    protected function casts(): array
    {
        return [
            'recipient_name' => 'encrypted',
            'phone' => 'encrypted',
            'address_line' => 'encrypted',
            'is_favorite' => 'boolean',
        ];
    }

    public function area()
    {
        return $this->belongsTo(DeliveryArea::class, 'delivery_area_id');
    }
}
