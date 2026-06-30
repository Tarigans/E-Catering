<x-layouts.app title="Akun Saya">
    <x-breadcrumbs><li class="breadcrumb-item active">Akun</li></x-breadcrumbs>
    <main class="container py-4">
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <div class="eyebrow">Akun Customer</div>
                        <h1 class="fw-bold mb-0">Riwayat Pesanan</h1>
                    </div>
                    @can('isAdmin')
                        <a class="btn btn-sm btn-tomato" href="{{ route('admin.dashboard') }}">Dashboard Admin</a>
                    @endcan
                </div>
                <div class="soft rounded p-3">
                    @forelse($orders as $order)
                        <div class="border-bottom py-3">
                            <div class="d-flex justify-content-between gap-2"><strong>{{ $order->order_number }}</strong><span class="badge text-bg-warning">{{ __('orders.status.'.$order->status) }}</span></div>
                            <div class="small text-secondary">Pesan {{ $order->created_at->format('d M Y H:i') }} | Acara {{ ($order->event_date ?? $order->delivery_date)->format('d M Y') }} | Rp{{ number_format($order->grand_total,0,',','.') }}</div>
                            <div class="mt-2 d-flex flex-wrap gap-2"><a class="btn btn-sm btn-outline-secondary" href="{{ route('orders.track', $order->order_number) }}">Invoice & Lacak</a><a class="btn btn-sm btn-outline-secondary" href="{{ route('orders.export-pdf', $order->order_number) }}" target="_blank">Export PDF</a><a class="btn btn-sm btn-tomato" href="{{ route('menus.index') }}">Pesan Lagi</a></div>
                        </div>
                    @empty
                        <div class="text-center py-4">
                            <h2 class="h5 fw-bold">Belum ada pesanan</h2>
                            <p class="text-secondary">Mulai dari memilih paket catering sesuai kebutuhan acara.</p>
                            <a href="{{ route('menus.index') }}" class="btn btn-tomato">Pilih Paket</a>
                        </div>
                    @endforelse
                </div>
                <div class="mt-3">{{ $orders->links() }}</div>
            </div>
            <div class="col-lg-4">
                <div class="soft rounded p-3 mb-3"><h2 class="h5 fw-bold">Poin Loyalitas</h2><div class="display-6 fw-bold text-success">{{ auth()->user()->loyalty_points }}</div><p class="small text-secondary">100 poin bisa ditukar Rp1.000 saat checkout.</p></div>
                <div class="soft rounded p-3"><h2 class="h5 fw-bold">Alamat Favorit</h2>@forelse($addresses as $address)<div class="border-top py-2"><strong>{{ $address->label }}</strong><div class="small">{{ $address->area->district }} - {{ $address->area->village }}</div><div class="small text-secondary">{{ $address->address_line }}</div></div>@empty<p class="mb-0 small text-secondary">Belum ada alamat tersimpan.</p>@endforelse</div>
            </div>
        </div>
    </main>
</x-layouts.app>
