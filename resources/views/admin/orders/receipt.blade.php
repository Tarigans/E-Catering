<!doctype html><html lang="id"><head><meta charset="utf-8"><title>Struk Dapur {{ $order->order_number }}</title><style>body{font-family:Arial,sans-serif;max-width:420px;margin:20px auto}.line{border-top:1px dashed #333;padding-top:10px;margin-top:10px}@media print{button{display:none}}</style></head><body>
<h1>Struk Persiapan Catering</h1><strong>{{ $order->order_number }}</strong><p>Acara: {{ ($order->event_date ?? $order->delivery_date)->format('d M Y') }} {{ substr($order->event_time ?? $order->delivery_time,0,5) }}</p>
@foreach($order->items as $item)<div class="line"><strong>{{ $item->quantity }}x {{ $item->menu_name }}</strong><br><small>{{ $item->note ?: 'Tanpa catatan' }}</small></div>@endforeach
<button onclick="window.print()">Cetak</button>
</body></html>
