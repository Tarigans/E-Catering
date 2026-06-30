<x-layouts.app title="Laporan Pesanan">
    <main class="container py-4">
        <div class="d-flex flex-column flex-lg-row justify-content-between gap-3 align-items-lg-end mb-3">
            <div>
                <div class="eyebrow">Admin</div>
                <h1 class="fw-bold mb-1">Laporan Pesanan</h1>
                <p class="text-secondary mb-0">Rekap jumlah pesanan, pax, status, dan detail order.</p>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('admin.reports.finance') }}" class="btn btn-outline-secondary">Laporan Keuangan</a>
                <a href="{{ route('admin.reports.orders.pdf', request()->query()) }}" target="_blank" class="btn btn-tomato">Export PDF</a>
            </div>
        </div>

        @include('admin.reports.partials.filter')

        <div class="row g-3 mb-3">
            <div class="col-md-4"><div class="soft rounded p-3"><span class="text-secondary">Total Pesanan</span><h2 class="fw-bold mb-0">{{ $summary['total_orders'] }}</h2></div></div>
            <div class="col-md-4"><div class="soft rounded p-3"><span class="text-secondary">Total Pax</span><h2 class="fw-bold mb-0">{{ number_format($summary['total_pax'],0,',','.') }}</h2></div></div>
            <div class="col-md-4"><div class="soft rounded p-3"><span class="text-secondary">Nilai Pesanan</span><h2 class="fw-bold mb-0">Rp{{ number_format($summary['total_amount'],0,',','.') }}</h2></div></div>
        </div>

        <div class="soft rounded p-3 mb-3">
            <h2 class="h5 fw-bold">Ringkasan Status</h2>
            <div class="d-flex flex-wrap gap-2">
                @foreach(['waiting_payment','paid','processing','cooking','ready_to_ship','on_delivery','delivered','cancelled'] as $status)
                    <span class="badge text-bg-light border">{{ __('orders.status.'.$status) }}: {{ $summary['by_status'][$status] ?? 0 }}</span>
                @endforeach
            </div>
        </div>

        <div class="soft rounded p-3 table-responsive">
            <table class="table align-middle">
                <thead><tr><th>No Pesanan</th><th>Customer</th><th>Acara</th><th>Status</th><th>Pax</th><th>Total</th></tr></thead>
                <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td><a class="fw-bold" href="{{ route('orders.track', $order->order_number) }}">{{ $order->order_number }}</a><div class="small text-secondary">{{ $order->created_at->format('d M Y H:i') }}</div></td>
                        <td>{{ $order->user?->name ?? $order->recipient_name }}</td>
                        <td>{{ ($order->event_date ?? $order->delivery_date)->format('d M Y') }} {{ substr($order->event_time ?? $order->delivery_time,0,5) }}</td>
                        <td><span class="badge text-bg-warning">{{ __('orders.status.'.$order->status) }}</span></td>
                        <td>{{ $order->items->sum('quantity') }}</td>
                        <td class="fw-bold">Rp{{ number_format($order->grand_total,0,',','.') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-secondary py-4">Tidak ada pesanan pada periode ini.</td></tr>
                @endforelse
                </tbody>
            </table>
            {{ $orders->links() }}
        </div>
    </main>
</x-layouts.app>
