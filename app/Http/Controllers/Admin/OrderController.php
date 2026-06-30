<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        abort_unless(auth()->user()?->isAdmin(), 403);

        return view('admin.orders.index', ['orders' => Order::with(['items', 'area', 'user'])->latest()->paginate(20)]);
    }

    public function updateStatus(Request $request, Order $order)
    {
        abort_unless(auth()->user()?->isAdmin(), 403);
        $data = $request->validate([
            'status' => ['required', 'in:waiting_payment,paid,processing,cooking,ready_to_ship,on_delivery,delivered,cancelled'],
        ]);

        $order->update([
            'status' => $data['status'],
            'paid_at' => in_array($data['status'], ['paid', 'processing', 'cooking', 'ready_to_ship', 'on_delivery', 'delivered']) && ! $order->paid_at ? now() : $order->paid_at,
            'delivered_at' => $data['status'] === 'delivered' ? now() : $order->delivered_at,
        ]);

        return back()->with('success', $data['status'] === 'processing' ? 'Status diperbarui. Struk dapur siap dicetak.' : 'Status diperbarui.');
    }

    public function confirmPayment(Order $order)
    {
        abort_unless(auth()->user()?->isAdmin(), 403);
        abort_unless($order->payment_proof_path || $order->payment_method === 'cod', 422);

        $order->update([
            'status' => 'paid',
            'paid_at' => $order->paid_at ?: now(),
            'payment_verified_at' => now(),
            'payment_rejected_at' => null,
        ]);

        return back()->with('success', 'Pembayaran berhasil dikonfirmasi.');
    }

    public function rejectPayment(Request $request, Order $order)
    {
        abort_unless(auth()->user()?->isAdmin(), 403);

        $data = $request->validate([
            'payment_note' => ['nullable', 'string', 'max:300'],
        ]);

        $order->update([
            'status' => 'waiting_payment',
            'paid_at' => null,
            'payment_verified_at' => null,
            'payment_rejected_at' => now(),
            'payment_note' => $data['payment_note'] ?: 'Bukti pembayaran belum valid. Mohon upload ulang.',
        ]);

        return back()->with('success', 'Bukti pembayaran ditolak.');
    }

    public function kitchenReceipt(Order $order)
    {
        abort_unless(auth()->user()?->isAdmin(), 403);

        return view('admin.orders.receipt', ['order' => $order->load('items')]);
    }
}
