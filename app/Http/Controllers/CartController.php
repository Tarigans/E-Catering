<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function show(CartService $cart)
    {
        return view('cart.show', ['items' => $cart->detailedItems(), 'subtotal' => $cart->subtotal()]);
    }

    public function store(Request $request, CartService $cart)
    {
        $data = $request->validate([
            'menu_item_id' => ['required', 'exists:menu_items,id'],
            'quantity' => ['required', 'integer', 'min:1', 'max:10000'],
            'note' => ['nullable', 'string', 'max:180'],
        ]);

        $cart->add(MenuItem::available()->findOrFail($data['menu_item_id']), $data['quantity'], $data['note'] ?? null);

        return response()->json(['ok' => true, 'count' => $cart->items()->sum('quantity'), 'subtotal' => $cart->subtotal()]);
    }

    public function update(Request $request, CartService $cart, string $key)
    {
        $data = $request->validate(['quantity' => ['required', 'integer', 'min:0', 'max:10000']]);
        $cart->update($key, $data['quantity']);

        return response()->json(['ok' => true, 'subtotal' => $cart->subtotal()]);
    }

    public function summary(CartService $cart)
    {
        return response()->json([
            'count' => $cart->items()->sum('quantity'),
            'subtotal' => $cart->subtotal(),
            'items' => $cart->detailedItems()->values(),
        ]);
    }
}
