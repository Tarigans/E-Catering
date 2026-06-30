<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OrderTrackingController extends Controller
{
    public function show(string $orderNumber)
    {
        $order = Order::with(['items', 'area'])->where('order_number', $orderNumber)->firstOrFail();

        return view('orders.track', compact('order'));
    }

    public function status(string $orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)->firstOrFail();

        return response()->json([
            'status' => $order->status,
            'label' => __('orders.status.'.$order->status),
            'eta_minutes' => $order->eta_minutes,
            'updated_at' => $order->updated_at->diffForHumans(),
        ]);
    }

    public function exportPdf(string $orderNumber)
    {
        $order = Order::with(['items', 'area', 'user'])->where('order_number', $orderNumber)->firstOrFail();

        abort_unless(! auth()->check() || auth()->user()->isAdmin() || $order->user_id === auth()->id(), 403);

        return view('orders.export-pdf', compact('order'));
    }

    public function uploadPaymentProof(Request $request, string $orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)->firstOrFail();

        abort_unless(auth()->check() && $order->user_id === auth()->id(), 403);
        abort_if($order->status !== 'waiting_payment', 422, 'Bukti pembayaran hanya bisa diupload untuk pesanan yang menunggu pembayaran.');

        $data = $request->validate([
            'payment_proof' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'payment_note' => ['nullable', 'string', 'max:300'],
        ]);

        if ($order->payment_proof_path) {
            Storage::disk('public')->delete($order->payment_proof_path);
        }

        $path = $request->file('payment_proof')->store('payment-proofs', 'public');

        $order->update([
            'payment_proof_path' => $path,
            'payment_proof_uploaded_at' => now(),
            'payment_verified_at' => null,
            'payment_rejected_at' => null,
            'payment_note' => $data['payment_note'] ?? null,
        ]);

        return back()->with('success', 'Bukti pembayaran berhasil diupload. Admin akan mengecek pembayaran kamu.');
    }
}
