<x-layouts.app title="Laporan Keuangan">
    <main class="container py-4">
        <div class="d-flex flex-column flex-lg-row justify-content-between gap-3 align-items-lg-end mb-3">
            <div>
                <div class="eyebrow">Admin</div>
                <h1 class="fw-bold mb-1">Laporan Keuangan</h1>
                <p class="text-secondary mb-0">Pendapatan dari pesanan yang sudah dibayar atau diproses.</p>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <a href="{{ route('admin.reports.orders') }}" class="btn btn-outline-secondary">Laporan Pesanan</a>
                <a href="{{ route('admin.reports.finance.pdf', request()->query()) }}" target="_blank" class="btn btn-tomato">Export PDF</a>
            </div>
        </div>

        @include('admin.reports.partials.filter')

        <div class="row g-3 mb-3">
            <div class="col-md-4"><div class="soft rounded p-3"><span class="text-secondary">Pendapatan Bersih</span><h2 class="fw-bold mb-0">Rp{{ number_format($summary['net_sales'],0,',','.') }}</h2></div></div>
            <div class="col-md-4"><div class="soft rounded p-3"><span class="text-secondary">Pesanan Dibayar</span><h2 class="fw-bold mb-0">{{ $summary['paid_orders'] }}</h2></div></div>
            <div class="col-md-4"><div class="soft rounded p-3"><span class="text-secondary">Diskon & Poin</span><h2 class="fw-bold mb-0">Rp{{ number_format($summary['discounts'] + $summary['points_discount'],0,',','.') }}</h2></div></div>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-lg-5">
                <div class="soft rounded p-3 h-100">
                    <h2 class="h5 fw-bold">Rincian</h2>
                    <div class="d-flex justify-content-between border-top py-2"><span>Subtotal paket</span><strong>Rp{{ number_format($summary['gross_sales'],0,',','.') }}</strong></div>
                    <div class="d-flex justify-content-between border-top py-2"><span>Ongkir</span><strong>Rp{{ number_format($summary['delivery_fees'],0,',','.') }}</strong></div>
                    <div class="d-flex justify-content-between border-top py-2"><span>Diskon voucher</span><strong>Rp{{ number_format($summary['discounts'],0,',','.') }}</strong></div>
                    <div class="d-flex justify-content-between border-top py-2"><span>Poin ditukar</span><strong>Rp{{ number_format($summary['points_discount'],0,',','.') }}</strong></div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="soft rounded p-3 h-100">
                    <h2 class="h5 fw-bold">Penjualan Harian</h2>
                    @forelse($dailySales as $day)
                        <div class="d-flex align-items-center gap-2 border-top py-2">
                            <span style="width:110px">{{ \Carbon\Carbon::parse($day->date)->format('d M Y') }}</span>
                            <div class="bg-success rounded" style="height:12px;width:{{ min(100, $day->total / max(1, $dailySales->max('total')) * 100) }}%"></div>
                            <small>{{ $day->orders_count }} order | Rp{{ number_format($day->total,0,',','.') }}</small>
                        </div>
                    @empty
                        <p class="text-secondary mb-0">Belum ada transaksi dibayar.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="soft rounded p-3 table-responsive">
            <table class="table align-middle">
                <thead><tr><th>No Pesanan</th><th>Customer</th><th>Subtotal</th><th>Ongkir</th><th>Diskon</th><th>Total</th></tr></thead>
                <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td><a class="fw-bold" href="{{ route('orders.track', $order->order_number) }}">{{ $order->order_number }}</a><div class="small text-secondary">{{ $order->created_at->format('d M Y H:i') }}</div></td>
                        <td>{{ $order->user?->name ?? $order->recipient_name }}</td>
                        <td>Rp{{ number_format($order->subtotal,0,',','.') }}</td>
                        <td>Rp{{ number_format($order->delivery_fee,0,',','.') }}</td>
                        <td>Rp{{ number_format($order->discount + $order->pointsDiscount(),0,',','.') }}</td>
                        <td class="fw-bold">Rp{{ number_format($order->grand_total,0,',','.') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-secondary py-4">Tidak ada transaksi pada periode ini.</td></tr>
                @endforelse
                </tbody>
            </table>
            {{ $orders->links() }}
        </div>
    </main>
</x-layouts.app>
