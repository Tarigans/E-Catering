<x-layouts.app title="Masuk">
    <main class="container py-5" style="max-width:520px">
        <div class="soft rounded p-4">
            <h1 class="fw-bold">Masuk</h1>
            <form method="post" action="{{ route('login') }}" class="mt-3">
                @csrf
                <input name="email" type="email" class="form-control mb-2" value="{{ old('email') }}" placeholder="Email" required>
                <input name="password" type="password" class="form-control mb-2" placeholder="Password" required>
                <label class="small"><input type="checkbox" name="remember" value="1"> Ingat saya</label>
                <button class="btn btn-tomato w-100 mt-3">Masuk</button>
            </form>
            <p class="small mt-3 mb-0">Belum punya akun? <a href="{{ route('register') }}">Daftar</a></p>
        </div>
    </main>
</x-layouts.app>
