<x-layouts.app title="Daftar">
    <main class="container py-5" style="max-width:560px">
        <div class="soft rounded p-4">
            <h1 class="fw-bold">Daftar Customer</h1>
            <form method="post" action="{{ route('register') }}" class="mt-3">
                @csrf
                <input name="name" class="form-control mb-2" value="{{ old('name') }}" placeholder="Nama lengkap" required>
                <input name="email" type="email" class="form-control mb-2" value="{{ old('email') }}" placeholder="Email" required>
                <input name="phone" class="form-control mb-2" value="{{ old('phone') }}" placeholder="Nomor HP">
                <input name="password" type="password" class="form-control mb-2" placeholder="Password" required>
                <input name="password_confirmation" type="password" class="form-control mb-3" placeholder="Konfirmasi password" required>
                <button class="btn btn-tomato w-100">Daftar</button>
            </form>
        </div>
    </main>
</x-layouts.app>
