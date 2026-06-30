# E-Catering Laravel

Website e-catering modular berbasis Laravel dengan katalog menu, keranjang real-time ringan, checkout, tracking pesanan, poin loyalitas, flash sale, paket langganan, dan admin panel sederhana.

## Stack

- Laravel 12 pada skeleton project ini, kompatibel secara pola dengan Laravel 10/11.
- Database MySQL atau SQLite untuk demo lokal.
- Bootstrap 5, Tailwind-friendly utility style, dan Alpine.js via CDN.
- Auth sederhana bawaan aplikasi.
- Payment service siap diarahkan ke Midtrans/Xendit melalui `.env`; mode default `demo`.

## Instalasi Cepat

```bash
composer install
cp .env.example .env
php artisan key:generate
```

Atur database di `.env`.

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=e_catering
DB_USERNAME=root
DB_PASSWORD=

PAYMENT_GATEWAY=demo
MIDTRANS_SERVER_KEY=
MIDTRANS_CLIENT_KEY=
XENDIT_SECRET_KEY=
```

Jalankan migrasi dan data dummy:

```bash
php artisan migrate --seed
php artisan serve
```

Akses aplikasi di `http://127.0.0.1:8000`.

## Akun Demo

- Admin: `admin@ecatering.test`
- Customer: `customer@ecatering.test`
- Password keduanya: `password`

Membuat admin pertama secara manual:

```bash
php artisan catering:create-admin admin@example.com
```

## Fitur Utama

- Landing page dengan hero, kategori, rekomendasi, testimoni, flash sale, paket langganan, countdown cut-off, dark mode, dan tombol WhatsApp.
- Halaman menu dengan filter kategori, harga, populer, promo, search, catatan item, dan tambah keranjang AJAX.
- Keranjang session dengan update jumlah tanpa reload penuh.
- Checkout 3 langkah: alamat dan area, jadwal, pembayaran dan review.
- Tracking pesanan dengan polling status tiap 10 detik.
- Akun customer berisi riwayat pesanan, invoice, pesan lagi, alamat favorit, voucher, dan poin.
- Admin dashboard, CRUD menu, update status pesanan, dan struk dapur siap cetak.
- Upload gambar menu dari admin disimpan ke `storage/app/public/menu-images`.
- Data sensitif seperti nomor HP dan alamat dicast terenkripsi di model.

Jika gambar upload belum tampil, jalankan:

```bash
php artisan storage:link
```

## Catatan Payment Gateway

`App\Services\PaymentGateway` saat ini memakai mode demo agar project langsung jalan dengan `php artisan serve`. Untuk produksi, isi method `createPayment()` dengan request resmi Midtrans Snap/Core API atau Xendit Invoice API, simpan token/url hasil gateway ke order, lalu tambahkan webhook untuk update status pembayaran dan refund.

## Struktur Penting

- `app/Models`: model domain catering.
- `app/Services/CartService.php`: logika keranjang.
- `app/Services/PaymentGateway.php`: adaptor payment.
- `app/Http/Controllers`: frontend, checkout, tracking, akun, admin.
- `database/migrations`: skema database.
- `database/seeders/DatabaseSeeder.php`: data dummy.
- `resources/views`: Blade customer dan admin.
