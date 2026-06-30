<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model
{
    protected $fillable = ['name', 'period', 'price', 'discount_percent', 'description', 'is_active'];
}
