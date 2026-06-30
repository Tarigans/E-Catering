<!doctype html>
<html lang="id" x-data="{ dark: localStorage.getItem('dark') === '1' }" x-bind:class="{ 'dark-mode': dark }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'E-Catering' }}</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/ecatering-mark.svg') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        :root {
            --tomato:#e8482c;
            --tomato-dark:#bf321c;
            --amber:#f6a821;
            --leaf:#18864a;
            --teal:#10878f;
            --plum:#683b7a;
            --ink:#271f1a;
            --muted:#70665e;
            --paper:#fff8ed;
            --paper-2:#f4fff8;
            --line:#ead2bd;
            --surface:#ffffff;
        }
        body {
            background:
                linear-gradient(135deg, rgba(246,168,33,.16), rgba(16,135,143,.08) 42%, rgba(232,72,44,.10)),
                var(--paper);
            color: var(--ink);
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, Segoe UI, sans-serif;
        }
        .dark-mode body { background:linear-gradient(135deg, #1d1714, #112321 55%, #241726); color:#f8efe6; }
        .navbar { backdrop-filter: blur(14px); background: rgba(255,248,237,.92); border-color: rgba(232,72,44,.18) !important; }
        .navbar .nav-link { color:#4b3b31; font-weight:600; }
        .navbar .nav-link:hover { color:var(--tomato); }
        .dark-mode .navbar, .dark-mode .soft, .dark-mode .card { background:#211d1a !important; color:#f8efe6; border-color:#3a302a; }
        .btn-tomato { background:linear-gradient(135deg, var(--tomato), #f17635); color:#fff; border:0; box-shadow:0 8px 18px rgba(232,72,44,.24); }
        .btn-tomato:hover { background:linear-gradient(135deg, var(--tomato-dark), #df5b22); color:#fff; transform: translateY(-1px); }
        .btn-leaf { background:linear-gradient(135deg, var(--leaf), var(--teal)); color:#fff; border:0; }
        .btn-outline-secondary { border-color:#d6bca5; color:#5d4b40; background:rgba(255,255,255,.48); }
        .btn-outline-secondary:hover { background:#fff0dc; border-color:var(--amber); color:#56351d; }
        .soft { background:rgba(255,255,255,.92); border:1px solid var(--line); box-shadow:0 12px 30px rgba(117,66,24,.09); }
        .menu-img { aspect-ratio: 4/3; object-fit: cover; width:100%; border-radius:8px 8px 0 0; }
        .hero { min-height: 76vh; display:flex; align-items:center; background: linear-gradient(90deg, rgba(38,32,28,.82), rgba(38,32,28,.28)), url('https://images.unsplash.com/photo-1543353071-10c8ba85a904?auto=format&fit=crop&w=1800&q=85') center/cover; color:#fff; }
        .badge-hot { background:#ffe1d8; color:#af2f18; border:1px solid #ffc4b6; }
        .dark-mode .badge-hot { background:#572116; color:#ffd9cf; }
        .hover-lift { transition:.2s ease; }
        .hover-lift:hover { transform: translateY(-4px); box-shadow:0 18px 42px rgba(24,134,74,.14); border-color:#b9dfc5; }
        .breadcrumb a { color: var(--tomato); text-decoration:none; }
        .wa-float { position:fixed; right:18px; bottom:18px; z-index:20; border-radius:999px; padding:12px 16px; box-shadow:0 10px 28px rgba(22,128,60,.28); }
        .brand-logo { width:154px; height:auto; display:block; }
        .page-title { max-width:720px; }
        .eyebrow { color:var(--tomato); font-weight:700; text-transform:uppercase; letter-spacing:0; font-size:.78rem; }
        .step-dot { width:32px; height:32px; border-radius:999px; display:inline-flex; align-items:center; justify-content:center; background:#fff0d2; color:#9b4f08; font-weight:700; flex:0 0 32px; border:1px solid #ffd38a; }
        .stat-card { border-left:5px solid var(--teal); background:linear-gradient(135deg, #ffffff, #f4fff8); }
        .stat-card:nth-child(2), .row > .col-md-4:nth-child(2) .stat-card { border-left-color:var(--amber); background:linear-gradient(135deg, #ffffff, #fff6df); }
        .stat-card:nth-child(3), .row > .col-md-4:nth-child(3) .stat-card { border-left-color:var(--tomato); background:linear-gradient(135deg, #ffffff, #fff0ea); }
        .stat-card h2, .stat-card h3 { margin:0; }
        .table thead th { color:#6b625c; font-size:.82rem; text-transform:uppercase; letter-spacing:0; background:#fff2df; }
        .table tbody tr:hover { background:#f7fff9; }
        .text-bg-warning { background-color:#ffdb7d !important; color:#5c3a00 !important; }
        .text-bg-success { background-color:#d8f5df !important; color:#0d6331 !important; }
        .progress { background:#ffe6c3; }
        .progress-bar.bg-success, .bg-success { background-color:var(--leaf) !important; }
        .form-control:focus, .form-select:focus { border-color:var(--teal); box-shadow:0 0 0 .2rem rgba(16,135,143,.16); }
        .form-label { font-weight:600; }
        @media (max-width: 420px) { .brand-logo { width:132px; } }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg sticky-top border-bottom">
    <div class="container py-2">
        <a class="navbar-brand d-flex align-items-center py-0" href="{{ route('home') }}" aria-label="E-Catering">
            <img src="{{ asset('images/ecatering-logo.svg') }}" class="brand-logo" alt="E-Catering">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav"><span class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse" id="nav">
            <div class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
                <a class="nav-link" href="{{ route('menus.index') }}">Paket Catering</a>
                <a class="nav-link" href="{{ route('cart.show') }}">Keranjang</a>
                @auth
                    @if(auth()->user()->isCustomer())<a class="nav-link" href="{{ route('account.index') }}">Akun</a>@endif
                    @can('isAdmin')
                        <a class="nav-link" href="{{ route('admin.dashboard') }}">Dashboard Admin</a>
                        <a class="nav-link" href="{{ route('admin.reports.orders') }}">Laporan Pesanan</a>
                        <a class="nav-link" href="{{ route('admin.reports.finance') }}">Laporan Keuangan</a>
                    @endcan
                    <form method="post" action="{{ route('logout') }}">@csrf<button class="btn btn-sm btn-outline-secondary">Keluar</button></form>
                @else
                    <a class="btn btn-sm btn-outline-secondary" href="{{ route('login') }}">Masuk</a>
                @endauth
                <button class="btn btn-sm btn-outline-secondary" x-on:click="dark = !dark; localStorage.setItem('dark', dark ? '1' : '0')" title="Dark mode">Mode</button>
            </div>
        </div>
    </div>
</nav>

@if(session('success'))<div class="container mt-3"><div class="alert alert-success">{{ session('success') }}</div></div>@endif
@if($errors->any())<div class="container mt-3"><div class="alert alert-danger">{{ $errors->first() }}</div></div>@endif

{{ $slot }}

<a class="btn btn-leaf wa-float" href="https://wa.me/6281234567890" target="_blank">Konsultasi WhatsApp</a>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
