<x-layouts.app title="Admin Dashboard">
    <main class="container py-4">
        <div class="d-flex flex-column flex-lg-row justify-content-between gap-3 align-items-lg-end mb-3">
            <div>
                <div class="eyebrow">Panel Admin</div>
                <h1 class="fw-bold mb-1">Dashboard Operasional</h1>
                <p class="text-secondary mb-0">Pantau pesanan catering, pembayaran, paket, dan jadwal acara.</p>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <a class="btn btn-outline-secondary" href="{{ route('admin.reports.orders') }}">Laporan Pesanan</a>
                <a class="btn btn-outline-secondary" href="{{ route('admin.reports.finance') }}">Laporan Keuangan</a>
                <a class="btn btn-outline-secondary" href="{{ route('admin.settings.index') }}">Pengaturan</a>
                <a class="btn btn-outline-secondary" href="{{ route('admin.orders.index') }}">Kelola Pesanan</a>
                <a class="btn btn-tomato" href="{{ route('admin.menus.index') }}">Kelola Paket</a>
            </div>
        </div>
        <div class="row g-3 my-2">
            <div class="col-md-4"><div class="soft rounded p-3 stat-card"><span class="text-secondary">Penjualan hari ini</span><h2 class="fw-bold">Rp{{ number_format($salesToday,0,',','.') }}</h2></div></div>
            <div class="col-md-4"><div class="soft rounded p-3 stat-card"><span class="text-secondary">Pesanan hari ini</span><h2 class="fw-bold">{{ $ordersToday }}</h2></div></div>
            <div class="col-md-4"><div class="soft rounded p-3 stat-card"><span class="text-secondary">Perlu diproses</span><h2 class="fw-bold">{{ $pendingOrders }}</h2></div></div>
        </div>
        <div class="row g-4 mt-1">
            <div class="col-lg-7">
                <div class="soft rounded p-3 h-100">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h2 class="h5 fw-bold mb-0">Pesanan Terbaru</h2>
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-secondary">Lihat Semua</a>
                    </div>
                    @forelse($recentOrders as $order)
                        <div class="d-flex justify-content-between gap-3 border-top py-2">
                            <div>
                                <strong>{{ $order->order_number }}</strong>
                                <div class="small text-secondary">{{ $order->user?->name ?? $order->recipient_name }} | Acara {{ ($order->event_date ?? $order->delivery_date)->format('d M Y') }}</div>
                            </div>
                            <div class="text-end">
                                <span class="badge text-bg-warning">{{ __('orders.status.'.$order->status) }}</span>
                                <div class="small fw-bold">Rp{{ number_format($order->grand_total,0,',','.') }}</div>
                            </div>
                        </div>
                    @empty
                        <p class="text-secondary mb-0">Belum ada pesanan.</p>
                    @endforelse
                </div>
            </div>
            <div class="col-lg-5">
                <div class="soft rounded p-3 mb-4">
                    <h2 class="h5 fw-bold">Paket Terlaris</h2>
                    @foreach($bestMenus as $menu)<div class="d-flex justify-content-between border-top py-2"><span>{{ $menu->name }}</span><strong>{{ $menu->sold }} pax</strong></div>@endforeach
                </div>
                <div class="soft rounded p-3">
                    <h2 class="h5 fw-bold">Grafik 7 Hari</h2>
                    @foreach($chart as $point)<div class="d-flex align-items-center gap-2 my-2"><span style="width:90px">{{ $point->date }}</span><div class="bg-success rounded" style="height:14px;width:{{ min(100, $point->total / max(1, $chart->max('total')) * 100) }}%"></div><small>Rp{{ number_format($point->total,0,',','.') }}</small></div>@endforeach
                </div>
            </div>
        </div>
    </main>
</x-layouts.app>
