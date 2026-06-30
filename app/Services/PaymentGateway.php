<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Str;

class PaymentGateway
{
    public function createPayment(Order $order): array
    {
        $gateway = config('services.payment.gateway', env('PAYMENT_GATEWAY', 'demo'));

        return [
            'gateway' => $gateway,
            'token' => 'pay_'.Str::random(24),
            'url' => route('orders.track', $order->order_number).'?payment=demo',
        ];
    }
}
