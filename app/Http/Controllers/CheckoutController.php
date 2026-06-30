<?php

namespace App\Http\Controllers;

use App\Models\CustomerAddress;
use App\Models\DeliveryArea;
use App\Models\DeliverySlot;
use App\Models\LoyaltyLedger;
use App\Models\Order;
use App\Models\Voucher;
use App\Services\CartService;
use App\Services\PaymentGateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function create(CartService $cart)
    {
        if ($cart->detailedItems()->isEmpty()) {
            return redirect()->route('cart.show')->with('success', 'Keranjang masih kosong.');
        }

        return view('checkout.create', [
            'items' => $cart->detailedItems(),
            'areas' => DeliveryArea::where('is_active', true)->orderBy('district')->get(),
            'slots' => DeliverySlot::where('is_active', true)->orderBy('starts_at')->get(),
            'subtotal' => $cart->subtotal(),
        ]);
    }

    public function store(Request $request, CartService $cart, PaymentGateway $payments)
    {
        $items = $cart->detailedItems();
        abort_if($items->isEmpty(), 422, 'Keranjang kosong.');

        $data = $request->validate([
            'recipient_name' => ['required', 'string', 'max:120'],
            'recipient_phone' => ['required', 'string', 'max:30'],
            'delivery_area_id' => ['required', 'exists:delivery_areas,id'],
            'delivery_address' => ['required', 'string', 'max:500'],
            'event_date' => ['required', 'date', 'after_or_equal:today'],
            'event_time' => ['required', 'date_format:H:i'],
            'payment_method' => ['required', 'in:bank_transfer,qris,credit_card,cod'],
            'voucher_code' => ['nullable', 'string', 'max:40'],
            'redeem_points' => ['nullable', 'integer', 'min:0'],
            'save_address' => ['nullable', 'boolean'],
        ]);

        $area = DeliveryArea::findOrFail($data['delivery_area_id']);
        $voucher = $data['voucher_code'] ? Voucher::where('code', strtoupper($data['voucher_code']))->first() : null;
        $subtotal = $cart->subtotal();
        $discount = $voucher?->discountFor($subtotal) ?? 0;
        $pointsRedeemed = min((int) ($data['redeem_points'] ?? 0), auth()->user()?->loyalty_points ?? 0);
        $pointsDiscount = intdiv($pointsRedeemed, 100) * 1000;
        $grandTotal = max(0, $subtotal + $area->delivery_fee - $discount - $pointsDiscount);

        $order = DB::transaction(function () use ($data, $items, $area, $voucher, $subtotal, $discount, $pointsRedeemed, $grandTotal) {
            $order = Order::create([
                'order_number' => 'EC-'.now()->format('ymd').'-'.Str::upper(Str::random(6)),
                'user_id' => auth()->id(),
                'delivery_area_id' => $area->id,
                'voucher_id' => $voucher?->id,
                'status' => $data['payment_method'] === 'cod' ? 'paid' : 'waiting_payment',
                'payment_method' => $data['payment_method'],
                'recipient_name' => $data['recipient_name'],
                'recipient_phone' => $data['recipient_phone'],
                'delivery_address' => $data['delivery_address'],
                'event_date' => $data['event_date'],
                'event_time' => $data['event_time'],
                'delivery_date' => $data['event_date'],
                'delivery_time' => $data['event_time'],
                'subtotal' => $subtotal,
                'delivery_fee' => $area->delivery_fee,
                'discount' => $discount,
                'points_redeemed' => $pointsRedeemed,
                'points_earned' => intdiv($grandTotal, 10000),
                'grand_total' => $grandTotal,
                'eta_minutes' => $area->eta_minutes,
                'paid_at' => $data['payment_method'] === 'cod' ? now() : null,
            ]);

            foreach ($items as $item) {
                $order->items()->create([
                    'menu_item_id' => $item['menu_item_id'],
                    'menu_name' => $item['name'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'note' => $item['note'],
                    'line_total' => $item['line_total'],
                ]);
            }

            if (auth()->check()) {
                auth()->user()->decrement('loyalty_points', $pointsRedeemed);
                auth()->user()->increment('loyalty_points', $order->points_earned);
                LoyaltyLedger::create(['user_id' => auth()->id(), 'order_id' => $order->id, 'points' => $order->points_earned - $pointsRedeemed, 'description' => 'Transaksi '.$order->order_number]);

                if (! empty($data['save_address'])) {
                    CustomerAddress::create([
                        'user_id' => auth()->id(),
                        'delivery_area_id' => $area->id,
                        'recipient_name' => $data['recipient_name'],
                        'phone' => $data['recipient_phone'],
                        'address_line' => $data['delivery_address'],
                        'is_favorite' => true,
                    ]);
                }
            }

            return $order;
        });

        $payment = $payments->createPayment($order);
        $order->update(['payment_gateway' => $payment['gateway'], 'payment_token' => $payment['token'], 'payment_url' => $payment['url']]);
        $cart->clear();

        return redirect()->route('orders.track', $order->order_number);
    }
}
