<x-layouts.app title="Lacak Pesanan {{ $order->order_number }}">
    <x-breadcrumbs><li class="breadcrumb-item active">Lacak Pesanan</li></x-breadcrumbs>
    <main class="container py-4" x-data="tracker('{{ route('orders.status', $order->order_number) }}')" x-init="poll()">
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="soft rounded p-4">
                    <div class="d-flex justify-content-between gap-3"><div><div class="eyebrow">Tracking Pesanan</div><h1 class="fw-bold">{{ $order->order_number }}</h1><p class="text-secondary mb-1">Tanggal pesanan {{ $order->created_at->format('d M Y H:i') }}</p><p class="text-secondary">Acara {{ ($order->event_date ?? $order->delivery_date)->format('d M Y') }} pukul {{ substr($order->event_time ?? $order->delivery_time,0,5) }}</p></div><span class="badge text-bg-warning align-self-start" x-text="label">{{ __('orders.status.'.$order->status) }}</span></div>
                    <div class="progress my-4" style="height:10px"><div class="progress-bar bg-success" x-bind:style="`width:${progress()}%`"></div></div>
                    <div class="row text-center small">
                        @foreach(['waiting_payment'=>'Menunggu pembayaran','processing'=>'Diproses','cooking'=>'Disiapkan','ready_to_ship'=>'Siap dikirim','on_delivery'=>'Dalam perjalanan','delivered'=>'Selesai'] as $key=>$label)
                            <div class="col">{{ $label }}</div>
                        @endforeach
                    </div>
                    <p class="mt-4 mb-0">Estimasi persiapan/pengantaran: <strong x-text="eta + ' menit'">{{ $order->eta_minutes }} menit</strong>. Status diperbarui otomatis tiap 10 detik.</p>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="soft rounded p-3 mb-3">
                    <div class="d-flex justify-content-between align-items-center gap-2 mb-2">
                        <h2 class="h5 fw-bold mb-0">Invoice</h2>
                        <a class="btn btn-sm btn-tomato" href="{{ route('orders.export-pdf', $order->order_number) }}" target="_blank">Export PDF</a>
                    </div>
                    @foreach($order->items as $item)
                        <div class="d-flex justify-content-between small border-bottom py-2"><span>{{ $item->quantity }} pax {{ $item->menu_name }}</span><strong>Rp{{ number_format($item->line_total,0,',','.') }}</strong></div>
                    @endforeach
                    <div class="d-flex justify-content-between mt-3"><span>Total</span><strong>Rp{{ number_format($order->grand_total,0,',','.') }}</strong></div>
                    <div class="small text-secondary mt-2">Metode: {{ str_replace('_', ' ', strtoupper($order->payment_method)) }}</div>
                </div>

                @if($order->status === 'waiting_payment')
                    <div class="soft rounded p-3">
                        <h2 class="h5 fw-bold">Upload Bukti Pembayaran</h2>
                        @if($order->payment_proof_path && ! $order->payment_rejected_at)
                            <div class="alert alert-warning small mb-3">Bukti sudah diupload dan sedang menunggu konfirmasi admin.</div>
                        @elseif($order->payment_rejected_at)
                            <div class="alert alert-danger small mb-3">{{ $order->payment_note ?: 'Bukti pembayaran ditolak. Mohon upload ulang.' }}</div>
                        @else
                            <p class="small text-secondary">Transfer sesuai total invoice, lalu upload screenshot atau foto bukti pembayaran.</p>
                        @endif

                        @if($order->payment_proof_path)
                            <a href="{{ asset('storage/'.$order->payment_proof_path) }}" target="_blank">
                                <img src="{{ asset('storage/'.$order->payment_proof_path) }}" class="rounded border mb-3" style="width:100%;max-height:220px;object-fit:cover" alt="Bukti pembayaran">
                            </a>
                        @endif

                        @auth
                            @if($order->user_id === auth()->id())
                                <form method="post" action="{{ route('orders.payment-proof', $order->order_number) }}" enctype="multipart/form-data">
                                    @csrf
                                    <input type="file" name="payment_proof" accept="image/png,image/jpeg,image/webp" class="form-control mb-2" required>
                                    <textarea name="payment_note" class="form-control mb-2" rows="2" placeholder="Catatan opsional, misal nama pengirim/rekening"></textarea>
                                    <button class="btn btn-tomato w-100">{{ $order->payment_proof_path ? 'Upload Ulang Bukti' : 'Upload Bukti' }}</button>
                                </form>
                            @endif
                        @else
                            <a href="{{ route('login') }}" class="btn btn-outline-secondary w-100">Login untuk Upload Bukti</a>
                        @endauth
                    </div>
                @elseif($order->payment_verified_at)
                    <div class="soft rounded p-3">
                        <h2 class="h5 fw-bold">Pembayaran</h2>
                        <div class="alert alert-success mb-0">Pembayaran sudah dikonfirmasi admin.</div>
                    </div>
                @endif
            </div>
        </div>
    </main>
    <script>
        function tracker(url){return{status:'{{ $order->status }}',label:'{{ __('orders.status.'.$order->status) }}',eta:{{ $order->eta_minutes }},steps:['waiting_payment','processing','cooking','ready_to_ship','on_delivery','delivered'],progress(){return Math.max(8, (this.steps.indexOf(this.status)+1)/this.steps.length*100)},poll(){setInterval(()=>fetch(url).then(r=>r.json()).then(d=>{this.status=d.status;this.label=d.label;this.eta=d.eta_minutes}),10000)}}}
    </script>
</x-layouts.app>
