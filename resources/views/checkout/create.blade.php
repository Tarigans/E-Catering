<x-layouts.app title="Checkout Paket Catering">
    <x-breadcrumbs><li class="breadcrumb-item"><a href="{{ route('cart.show') }}">Keranjang</a></li><li class="breadcrumb-item active">Checkout</li></x-breadcrumbs>
    <main class="container py-4">
        <div class="page-title mb-3">
            <div class="eyebrow">Langkah 3</div>
            <h1 class="fw-bold">Detail acara dan pembayaran</h1>
            <p class="text-secondary mb-0">Isi data penerima, tanggal acara, alamat, lalu pilih metode pembayaran.</p>
        </div>
        <form method="post" action="{{ route('checkout.store') }}" class="row g-4">
            @csrf
            <div class="col-lg-8">
                <div class="soft rounded p-3 mb-3">
                    <h2 class="h5 fw-bold">1. Alamat & Jadwal Acara</h2>
                    <div class="row g-3">
                        <div class="col-md-6"><label class="form-label">Nama penerima/PIC acara</label><input name="recipient_name" class="form-control" value="{{ old('recipient_name', auth()->user()->name) }}" placeholder="Nama penerima" required></div>
                        <div class="col-md-6"><label class="form-label">Nomor HP aktif</label><input name="recipient_phone" class="form-control" value="{{ old('recipient_phone', auth()->user()->phone) }}" placeholder="Nomor HP" required></div>
                        <div class="col-md-6"><label class="form-label">Area pengiriman</label><select name="delivery_area_id" class="form-select" required>@foreach($areas as $area)<option value="{{ $area->id }}">{{ $area->district }} - {{ $area->village }} | Rp{{ number_format($area->delivery_fee,0,',','.') }}</option>@endforeach</select></div>
                        <div class="col-md-3"><label class="form-label">Tanggal acara</label><input name="event_date" type="date" class="form-control" value="{{ old('event_date', now()->addDay()->toDateString()) }}" required></div>
                        <div class="col-md-3"><label class="form-label">Jam acara</label><select name="event_time" class="form-select">@foreach($slots->unique(fn($s)=>$s->starts_at.'-'.$s->ends_at) as $slot)<option value="{{ substr($slot->starts_at,0,5) }}">{{ substr($slot->starts_at,0,5) }}-{{ substr($slot->ends_at,0,5) }}</option>@endforeach</select></div>
                        <div class="col-12"><label class="form-label">Alamat acara/penerima</label><textarea name="delivery_address" class="form-control" rows="3" placeholder="Nama gedung, jalan, patokan, lantai/ruangan" required>{{ old('delivery_address') }}</textarea></div>
                        <div class="col-12"><label class="small"><input type="checkbox" name="save_address" value="1"> Simpan sebagai alamat favorit</label></div>
                    </div>
                </div>
                <div class="soft rounded p-3">
                    <h2 class="h5 fw-bold">2. Pembayaran & Promo</h2>
                    <div class="row g-3">
                        <div class="col-md-6"><label class="form-label">Metode pembayaran</label><select name="payment_method" class="form-select"><option value="bank_transfer">Transfer Bank</option><option value="qris">QRIS via Midtrans</option><option value="credit_card">Kartu Kredit via Midtrans</option><option value="cod">Bayar di Tempat</option></select></div>
                        <div class="col-md-3"><label class="form-label">Voucher</label><input name="voucher_code" class="form-control" placeholder="Kode voucher"></div>
                        <div class="col-md-3"><label class="form-label">Tukar poin</label><input name="redeem_points" type="number" min="0" max="{{ auth()->user()->loyalty_points }}" class="form-control" placeholder="0"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="soft rounded p-3 position-sticky" style="top:92px">
                    <h2 class="h5 fw-bold">3. Review Pesanan</h2>
                    @foreach($items as $item)<div class="d-flex justify-content-between border-bottom py-2 small"><span>{{ $item['quantity'] }} pax {{ $item['name'] }}</span><strong>Rp{{ number_format($item['line_total'],0,',','.') }}</strong></div>@endforeach
                    <div class="d-flex justify-content-between mt-3"><span>Subtotal paket</span><strong>Rp{{ number_format($subtotal,0,',','.') }}</strong></div>
                    <button class="btn btn-tomato w-100 mt-3">Konfirmasi & Bayar</button>
                    <a href="{{ route('cart.show') }}" class="btn btn-outline-secondary w-100 mt-2">Kembali ke Keranjang</a>
                </div>
            </div>
        </form>
    </main>
</x-layouts.app>
