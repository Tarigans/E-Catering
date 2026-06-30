<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Laporan Keuangan</title>
    <style>
        body{font-family:Arial,sans-serif;color:#222;margin:28px}
        h1{margin:0 0 4px;font-size:24px}.muted{color:#666;font-size:12px}.summary{display:flex;gap:12px;margin:18px 0}.box{border:1px solid #ddd;padding:10px;flex:1}
        table{width:100%;border-collapse:collapse;font-size:12px;margin-top:14px}th,td{border:1px solid #ddd;padding:7px;text-align:left}th{background:#f3f3f3}
        .text-end{text-align:right}.toolbar{margin-bottom:16px}@media print{.toolbar{display:none}body{margin:0}}
    </style>
</head>
<body>
    <div class="toolbar"><button onclick="window.print()">Export PDF</button></div>
    <h1>Laporan Keuangan</h1>
    <div class="muted">Periode {{ \Carbon\Carbon::parse($filters['from'])->format('d M Y') }} sampai {{ \Carbon\Carbon::parse($filters['to'])->format('d M Y') }}</div>
    <div class="summary">
        <div class="box"><div class="muted">Pendapatan Bersih</div><strong>Rp{{ number_format($summary['net_sales'],0,',','.') }}</strong></div>
        <div class="box"><div class="muted">Pesanan Dibayar</div><strong>{{ $summary['paid_orders'] }}</strong></div>
        <div class="box"><div class="muted">Diskon & Poin</div><strong>Rp{{ number_format($summary['discounts'] + $summary['points_discount'],0,',','.') }}</strong></div>
    </div>
    <table>
        <thead><tr><th>Tanggal</th><th>Pesanan</th><th>Customer</th><th class="text-end">Subtotal</th><th class="text-end">Ongkir</th><th class="text-end">Diskon</th><th class="text-end">Total</th></tr></thead>
        <tbody>
        @foreach($orders as $order)
            <tr>
                <td>{{ $order->created_at->format('d M Y') }}</td>
                <td>{{ $order->order_number }}</td>
                <td>{{ $order->user?->name ?? $order->recipient_name }}</td>
                <td class="text-end">Rp{{ number_format($order->subtotal,0,',','.') }}</td>
                <td class="text-end">Rp{{ number_format($order->delivery_fee,0,',','.') }}</td>
                <td class="text-end">Rp{{ number_format($order->discount + $order->pointsDiscount(),0,',','.') }}</td>
                <td class="text-end">Rp{{ number_format($order->grand_total,0,',','.') }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <script>window.addEventListener('load',()=>window.print())</script>
</body>
</html>
