<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\DeliveryArea;
use App\Models\DeliverySlot;
use App\Models\MenuItem;
use App\Models\OperatingHour;
use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Models\Voucher;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@ecatering.test'],
            ['name' => 'Admin E-Catering', 'password' => Hash::make('password'), 'role' => User::ROLE_ADMIN]
        );

        User::updateOrCreate(
            ['email' => 'customer@ecatering.test'],
            ['name' => 'Customer Demo', 'password' => Hash::make('password'), 'role' => User::ROLE_CUSTOMER, 'phone' => '081234567890', 'loyalty_points' => 250]
        );

        $categories = collect([
            ['name' => 'Paket Pernikahan', 'description' => 'Paket prasmanan dan nasi box untuk resepsi dan akad.', 'icon' => 'heart'],
            ['name' => 'Paket Harian', 'description' => 'Paket makan harian untuk kantor, sekolah, dan keluarga.', 'icon' => 'calendar-day'],
            ['name' => 'Paket Mingguan', 'description' => 'Paket catering 5 sampai 7 hari dengan menu berganti.', 'icon' => 'calendar-week'],
            ['name' => 'Paket Bulanan', 'description' => 'Paket catering hemat untuk kebutuhan rutin satu bulan.', 'icon' => 'calendar'],
        ])->mapWithKeys(fn($category) => [
                $category['name'] => Category::updateOrCreate(
                    ['slug' => Str::slug($category['name'])],
                    $category + ['slug' => Str::slug($category['name'])]
                ),
            ]);

        $menus = [
            ['Paket Pernikahan', 'Paket Akad Intimate', 65000, 100, 'Nasi, 2 lauk utama, sayur, dessert, dan minuman untuk acara akad kecil.', ['pernikahan', 'premium'], true, false, 'https://images.unsplash.com/photo-1464366400600-7168b8af9bc3?auto=format&fit=crop&w=900&q=80'],
            ['Paket Pernikahan', 'Paket Resepsi Prasmanan', 85000, 300, 'Buffet lengkap dengan appetizer, main course, dessert, dan live station.', ['favorit'], true, true, 'https://images.unsplash.com/photo-1511795409834-ef04bbd61622?auto=format&fit=crop&w=900&q=80'],
            ['Paket Harian', 'Paket Lunch Box Kantor', 28000, 20, 'Menu makan siang harian berisi nasi, lauk, sayur, sambal, dan buah.', ['harian'], true, false, 'https://images.unsplash.com/photo-1547592180-85f173990554?auto=format&fit=crop&w=900&q=80'],
            ['Paket Harian', 'Paket Sehat Harian', 35000, 15, 'Paket rendah minyak dengan protein, karbohidrat, dan sayuran seimbang.', ['sehat', 'baru'], false, false, 'https://images.unsplash.com/photo-1490645935967-10de6ba17061?auto=format&fit=crop&w=900&q=80'],
            ['Paket Mingguan', 'Paket Kantor 5 Hari', 135000, 10, 'Paket makan siang Senin-Jumat dengan menu berbeda setiap hari.', ['mingguan', 'hemat'], true, true, 'https://images.unsplash.com/photo-1512058564366-18510be2db19?auto=format&fit=crop&w=900&q=80'],
            ['Paket Mingguan', 'Paket Keluarga Mingguan', 210000, 5, 'Paket keluarga untuk 7 hari dengan pilihan lauk rumahan.', ['keluarga'], false, false, 'https://images.unsplash.com/photo-1604908176997-125f25cc6f3d?auto=format&fit=crop&w=900&q=80'],
            ['Paket Bulanan', 'Paket Hemat Bulanan', 520000, 5, 'Paket catering bulanan 20 kali pengantaran untuk pelanggan rutin.', ['bulanan', 'hemat'], true, false, 'https://images.unsplash.com/photo-1565299507177-b0ac66763828?auto=format&fit=crop&w=900&q=80'],
            ['Paket Bulanan', 'Paket Premium Bulanan', 780000, 5, 'Paket bulanan premium dengan pilihan lauk spesial dan prioritas jadwal.', ['premium'], false, false, 'https://images.unsplash.com/photo-1555244162-803834f70033?auto=format&fit=crop&w=900&q=80'],
        ];

        foreach ($menus as [$categoryName, $name, $price, $minimumOrder, $description, $labels, $popular, $promo, $image]) {
            MenuItem::updateOrCreate(
                ['slug' => Str::slug($name)],
                [
                    'category_id' => $categories[$categoryName]->id,
                    'name' => $name,
                    'slug' => Str::slug($name),
                    'description' => $description,
                    'price' => $price,
                    'minimum_order' => $minimumOrder,
                    'image_url' => $image,
                    'labels' => $labels,
                    'is_popular' => $popular,
                    'is_promo' => $promo,
                    'flash_sale_price' => $promo ? (int) ($price * 0.85) : null,
                    'flash_sale_starts_at' => $promo ? '14:00:00' : null,
                    'flash_sale_ends_at' => $promo ? '17:00:00' : null,
                ]
            );
        }

        foreach ([['Coblong', 'Dago', 12000, 25], ['Sukajadi', 'Pasteur', 15000, 35], ['Lengkong', 'Cijagra', 10000, 20], ['Cicendo', 'Arjuna', 13000, 30]] as [$district, $village, $fee, $eta]) {
            DeliveryArea::updateOrCreate(['district' => $district, 'village' => $village], ['delivery_fee' => $fee, 'eta_minutes' => $eta]);
        }

        foreach (['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'] as $day) {
            OperatingHour::updateOrCreate(['day_of_week' => $day], ['opens_at' => '08:00:00', 'closes_at' => '21:00:00', 'cutoff_at' => '20:00:00']);
            foreach ([['10:00', '12:00'], ['12:00', '14:00'], ['17:00', '19:00']] as [$start, $end]) {
                DeliverySlot::updateOrCreate(['day_of_week' => $day, 'starts_at' => $start], ['ends_at' => $end, 'max_orders' => 25]);
            }
        }

        SubscriptionPlan::updateOrCreate(['name' => 'Langganan Mingguan Kantor'], ['period' => 'weekly', 'price' => 175000, 'discount_percent' => 8, 'description' => '5 hari makan siang untuk tim kecil.']);
        SubscriptionPlan::updateOrCreate(['name' => 'Langganan Bulanan Hemat'], ['period' => 'monthly', 'price' => 690000, 'discount_percent' => 15, 'description' => '20 hari menu berganti dengan prioritas slot.']);

        Voucher::updateOrCreate(['code' => 'HEMAT25'], ['name' => 'Diskon Pembuka', 'type' => 'percent', 'value' => 25, 'minimum_spend' => 75000, 'max_discount' => 30000, 'starts_at' => now()->subDay(), 'expires_at' => now()->addMonth()]);
        Voucher::updateOrCreate(['code' => 'ONGKIR10'], ['name' => 'Potongan Ongkir', 'type' => 'fixed', 'value' => 10000, 'minimum_spend' => 50000, 'starts_at' => now()->subDay(), 'expires_at' => now()->addMonth()]);
    }
}
