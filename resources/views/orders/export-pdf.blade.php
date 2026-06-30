<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Detail Pesanan {{ $order->order_number }}</title>
    <style>
        body{font-family:Arial,sans-serif;color:#222;max-width:760px;margin:28px auto}
        h1{margin:0 0 4px;font-size:24px}.muted{color:#666;font-size:12px}.grid{display:grid;grid-template-columns:1fr 1fr;gap:14px;margin:18px 0}.box{border:1px solid #ddd;padding:12px}
        table{width:100%;border-collapse:collapse;font-size:13px}th,td{border:1px solid #ddd;padding:8px;text-align:left}th{background:#f3f3f3}.text-end{text-align:right}
        .total{font-size:18px;font-weight:bold}.toolbar{margin-bottom:16px}@media print{.toolbar{display:none}body{margin:0;max-width:none}}
    </style>
</head>
<body>
    <div class="toolbar"><button onclick="window.print()">Export PDF</button></div>
    <h1>Detail Pesanan</h1>
    <div class="muted">{{ $order->order_number }} | {{ $order->created_at->format('d M Y H:i') }}</div>
    <div class="grid">
        <div class="box">
            <strong>Customer</strong>
            <div>{{ $order->user?->name ?? $order->recipient_name }}</div>
            <div class="muted">{{ $order->recipient_phone }}</div>
            <div class="muted">{{ $order->delivery_address }}</div>
        </div>
        <div class="box">
            <strong>Jadwal Acara</strong>
            <div>{{ ($order->event_date ?? $order->delivery_date)->format('d M Y') }} pukul {{ substr($order->event_time ?? $order->delivery_time,0,5) }}</div>
            <div class="muted">{{ $order->area?->district }} - {{ $order->area?->village }}</div>
            <div class="muted">Status: {{ __('orders.status.'.$order->status) }}</div>
        </div>
    </div>
    <table>
        <thead><tr><th>Menu</th><th>Harga</th><th>Pax</th><th class="text-end">Subtotal</th></tr></thead>
        <tbody>
        @foreach($order->items as $item)
            <tr>
                <td>{{ $item->menu_name }}<div class="muted">{{ $item->note ?: 'Tanpa catatan' }}</div></td>
                <td>Rp{{ number_format($item->price,0,',','.') }}</td>
                <td>{{ $item->quantity }}</td>
                <td class="text-end">Rp{{ number_format($item->line_total,0,',','.') }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <table>
        <tr><td>Subtotal paket</td><td class="text-end">Rp{{ number_format($order->subtotal,0,',','.') }}</td></tr>
        <tr><td>Ongkir</td><td class="text-end">Rp{{ number_format($order->delivery_fee,0,',','.') }}</td></tr>
        <tr><td>Diskon</td><td class="text-end">Rp{{ number_format($order->discount + $order->pointsDiscount(),0,',','.') }}</td></tr>
        <tr><td class="total">Total</td><td class="text-end total">Rp{{ number_format($order->grand_total,0,',','.') }}</td></tr>
    </table>
    <script>window.addEventListener('load',()=>window.print())</script>
</body>
</html>
