<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Pesanan</title>
    <style>
        body{font-family:Arial,sans-serif;color:#222;margin:28px}
        h1{margin:0 0 4px;font-size:24px}.muted{color:#666;font-size:12px}.summary{display:flex;gap:12px;margin:18px 0}.box{border:1px solid #ddd;padding:10px;flex:1}
        table{width:100%;border-collapse:collapse;font-size:12px}th,td{border:1px solid #ddd;padding:7px;text-align:left}th{background:#f3f3f3}
        .text-end{text-align:right}.toolbar{margin-bottom:16px}@media print{.toolbar{display:none}body{margin:0}}
    </style>
</head>
<body>
    <div class="toolbar"><button onclick="window.print()">Export PDF</button></div>
    <h1>Laporan Pesanan</h1>
    <div class="muted">Periode {{ \Carbon\Carbon::parse($filters['from'])->format('d M Y') }} sampai {{ \Carbon\Carbon::parse($filters['to'])->format('d M Y') }}</div>
    <div class="summary">
        <div class="box"><div class="muted">Total Pesanan</div><strong>{{ $summary['total_orders'] }}</strong></div>
        <div class="box"><div class="muted">Total Pax</div><strong>{{ number_format($summary['total_pax'],0,',','.') }}</strong></div>
        <div class="box"><div class="muted">Nilai Pesanan</div><strong>Rp{{ number_format($summary['total_amount'],0,',','.') }}</strong></div>
    </div>
    <table>
        <thead><tr><th>No Pesanan</th><th>Tanggal</th><th>Customer</th><th>Acara</th><th>Status</th><th>Pax</th><th class="text-end">Total</th></tr></thead>
        <tbody>
        @foreach($orders as $order)
            <tr>
                <td>{{ $order->order_number }}</td>
                <td>{{ $order->created_at->format('d M Y H:i') }}</td>
                <td>{{ $order->user?->name ?? $order->recipient_name }}</td>
                <td>{{ ($order->event_date ?? $order->delivery_date)->format('d M Y') }} {{ substr($order->event_time ?? $order->delivery_time,0,5) }}</td>
                <td>{{ __('orders.status.'.$order->status) }}</td>
                <td>{{ $order->items->sum('quantity') }}</td>
                <td class="text-end">Rp{{ number_format($order->grand_total,0,',','.') }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <script>window.addEventListener('load',()=>window.print())</script>
</body>
</html>
