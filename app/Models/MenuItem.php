<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class MenuItem extends Model
{
    protected $fillable = [
        'category_id', 'name', 'slug', 'description', 'price', 'minimum_order', 'image_url',
        'labels', 'is_popular', 'is_promo', 'rating_average', 'rating_count',
        'flash_sale_starts_at', 'flash_sale_ends_at', 'flash_sale_price', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'labels' => 'array',
            'is_popular' => 'boolean',
            'is_promo' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function scopeAvailable(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function getCurrentPriceAttribute(): int
    {
        $now = Carbon::now()->format('H:i:s');

        if ($this->flash_sale_price && $this->flash_sale_starts_at && $this->flash_sale_ends_at
            && $now >= $this->flash_sale_starts_at && $now <= $this->flash_sale_ends_at) {
            return (int) $this->flash_sale_price;
        }

        return (int) $this->price;
    }
}
