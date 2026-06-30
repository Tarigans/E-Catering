<?php

namespace App\Services;

use App\Models\MenuItem;
use Illuminate\Support\Collection;

class CartService
{
    public function items(): Collection
    {
        return collect(session('cart', []));
    }

    public function detailedItems(): Collection
    {
        $ids = $this->items()->pluck('menu_item_id')->all();
        $menus = MenuItem::with('category')->whereIn('id', $ids)->get()->keyBy('id');

        return $this->items()->map(function (array $item) use ($menus) {
            $menu = $menus->get($item['menu_item_id']);
            $price = $menu?->current_price ?? $item['price'];

            return array_merge($item, [
                'menu' => $menu,
                'name' => $menu?->name ?? $item['name'],
                'price' => $price,
                'line_total' => $price * $item['quantity'],
            ]);
        })->filter(fn (array $item) => $item['menu']);
    }

    public function add(MenuItem $menuItem, int $quantity = 1, ?string $note = null): void
    {
        $cart = $this->items();
        $key = (string) $menuItem->id.'-'.md5((string) $note);
        $existing = $cart->get($key, [
            'menu_item_id' => $menuItem->id,
            'name' => $menuItem->name,
            'price' => $menuItem->current_price,
            'quantity' => 0,
            'note' => $note,
        ]);

        $existing['quantity'] = max($menuItem->minimum_order, $existing['quantity'] + $quantity);
        $cart->put($key, $existing);
        session(['cart' => $cart->all()]);
    }

    public function update(string $key, int $quantity): void
    {
        $cart = $this->items();

        if ($quantity < 1) {
            $cart->forget($key);
        } elseif ($cart->has($key)) {
            $item = $cart->get($key);
            $menu = MenuItem::find($item['menu_item_id']);
            $item['quantity'] = max($menu?->minimum_order ?? 1, $quantity);
            $cart->put($key, $item);
        }

        session(['cart' => $cart->all()]);
    }

    public function clear(): void
    {
        session()->forget(['cart', 'voucher_code']);
    }

    public function subtotal(): int
    {
        return $this->detailedItems()->sum('line_total');
    }
}
