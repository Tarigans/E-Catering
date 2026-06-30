<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = ['order_id', 'menu_item_id', 'menu_name', 'price', 'quantity', 'note', 'line_total'];

    public function menuItem()
    {
        return $this->belongsTo(MenuItem::class);
    }
}
