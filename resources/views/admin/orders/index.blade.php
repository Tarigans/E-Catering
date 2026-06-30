<x-layouts.app title="Admin Pesanan">
    <main class="container py-4">
        <div class="d-flex flex-column flex-lg-row justify-content-between gap-3 align-items-lg-end mb-3">
            <div>
                <div class="eyebrow">Admin</div>
                <h1 class="fw-bold mb-1">Kelola Pesanan</h1>
                <p class="text-secondary mb-0">Cek pembayaran, tanggal acara, lalu ubah status operasional.</p>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('admin.reports.orders.pdf') }}" target="_blank" class="btn btn-tomato">Export PDF</a>
                <a href="{{ route('admin.reports.orders') }}" class="btn btn-outline-secondary">Laporan Pesanan</a>
                <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">Dashboard</a>
            </div>
        </div>
        <div class="soft rounded p-3 table-responsive">
            <table class="table align-middle">
                <thead><tr><th>Pesanan</th><th>Customer</th><th>Total</th><th>Pembayaran</th><th>Status Operasional</th><th>Aksi</th></tr></thead>
                <tbody>
                @foreach($orders as $order)
                    <tr>
                        <td style="min-width:220px">
                            <a href="{{ route('orders.track', $order->order_number) }}" class="fw-bold">{{ $order->order_number }}</a>
                            <div class="small text-secondary">Pesan: {{ $order->created_at->format('d M Y H:i') }}</div>
                            <div class="small text-secondary">Acara: {{ ($order->event_date ?? $order->delivery_date)->format('d M Y') }} {{ substr($order->event_time ?? $order->delivery_time,0,5) }}</div>
                        </td>
                        <td>{{ $order->user?->name ?? $order->recipient_name }}</td>
                        <td class="fw-bold">Rp{{ number_format($order->grand_total,0,',','.') }}</td>
                        <td style="min-width:240px">
                            <div class="small text-secondary mb-1">{{ str_replace('_', ' ', strtoupper($order->payment_method)) }}</div>
                            @if($order->payment_proof_path)
                                <a class="btn btn-sm btn-outline-secondary mb-2" href="{{ asset('storage/'.$order->payment_proof_path) }}" target="_blank">Lihat Bukti</a>
                                @if(! $order->payment_verified_at)
                                    <div class="d-flex flex-wrap gap-1">
                                        <form method="post" action="{{ route('admin.orders.payment.confirm', $order) }}">@csrf @method('patch')<button class="btn btn-sm btn-success">Konfirmasi</button></form>
                                        <form method="post" action="{{ route('admin.orders.payment.reject', $order) }}" class="d-flex gap-1">@csrf @method('patch')<input name="payment_note" class="form-control form-control-sm" placeholder="Alasan"><button class="btn btn-sm btn-outline-danger">Tolak</button></form>
                                    </div>
                                @else
                                    <span class="badge text-bg-success">Terkonfirmasi</span>
                                @endif
                            @elseif($order->status === 'waiting_payment')
                                <span class="badge text-bg-warning">Menunggu bukti</span>
                            @else
                                <span class="badge text-bg-secondary">Tidak perlu bukti</span>
                            @endif
                        </td>
                        <td style="min-width:260px"><form method="post" action="{{ route('admin.orders.status', $order) }}" class="d-flex gap-2">@csrf @method('patch')<select name="status" class="form-select form-select-sm">@foreach(['waiting_payment','paid','processing','cooking','ready_to_ship','on_delivery','delivered','cancelled'] as $status)<option value="{{ $status }}" @selected($order->status===$status)>{{ __('orders.status.'.$status) }}</option>@endforeach</select><button class="btn btn-sm btn-tomato">Simpan</button></form></td>
                        <td>
                            <div class="d-flex flex-wrap gap-1">
                                <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.orders.receipt', $order) }}" target="_blank">Cetak</a>
                                <a class="btn btn-sm btn-tomato" href="{{ route('orders.export-pdf', $order->order_number) }}" target="_blank">PDF</a>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            {{ $orders->links() }}
        </div>
    </main>
</x-layouts.app>
