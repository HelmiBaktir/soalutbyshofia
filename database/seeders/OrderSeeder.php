<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Entitlement;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Idempotent guard: jangan duplikasi order bila sudah pernah di-seed.
        if (Order::query()->exists()) {
            return;
        }

        $budi = User::where('email', 'budi@example.com')->firstOrFail();
        $siti = User::where('email', 'siti@example.com')->firstOrFail();

        $ekma4116 = Course::where('code', 'EKMA4116')->firstOrFail();
        $eksi4205 = Course::where('code', 'EKSI4205')->firstOrFail();
        $ekma4214 = Course::where('code', 'EKMA4214')->firstOrFail();
        $hkum4101 = Course::where('code', 'HKUM4101')->firstOrFail();
        $sosi4201 = Course::where('code', 'SOSI4201')->firstOrFail();

        // ------------------------------------------------------------------
        // Order #1 — status: paid (Budi Santoso)
        // Berisi dua entitlement untuk mendemonstrasikan skenario aktif & kedaluwarsa.
        // ------------------------------------------------------------------
        $paidOrder = Order::create([
            'user_id' => $budi->id,
            'total_amount' => $ekma4116->price + $eksi4205->price,
            'status' => 'paid',
            'payment_method' => 'finpay',
            'payment_reference' => 'FINPAY-DEMO-0001',
            'paid_at' => Carbon::now()->subDay(),
            'expires_at' => null,
        ]);

        OrderItem::create([
            'order_id' => $paidOrder->id,
            'course_id' => $ekma4116->id,
            'price' => $ekma4116->price,
        ]);

        OrderItem::create([
            'order_id' => $paidOrder->id,
            'course_id' => $eksi4205->id,
            'price' => $eksi4205->price,
        ]);

        // Entitlement AKTIF — download masih berlaku.
        Entitlement::create([
            'user_id' => $budi->id,
            'order_id' => $paidOrder->id,
            'course_id' => $ekma4116->id,
            'granted_at' => Carbon::now()->subDay(),
            'download_expires_at' => Carbon::now()->subDay()->addMonths(3),
        ]);

        // Entitlement KEDALUWARSA — granted 4 bulan lalu => download sudah lewat 3 bulan.
        // Sesuai instruksi: gunakan Carbon::now()->subMonths(4) untuk menguji skenario kedaluwarsa.
        Entitlement::create([
            'user_id' => $budi->id,
            'order_id' => $paidOrder->id,
            'course_id' => $eksi4205->id,
            'granted_at' => Carbon::now()->subMonths(4),
            'download_expires_at' => Carbon::now()->subMonths(4)->addMonths(3), // 1 bulan lalu => kedaluwarsa
        ]);

        // ------------------------------------------------------------------
        // Order #2 — status: pending (Siti Rahayu)
        // Belum dibayar => belum ada entitlement.
        // ------------------------------------------------------------------
        $pendingOrder = Order::create([
            'user_id' => $siti->id,
            'total_amount' => $ekma4214->price + $hkum4101->price,
            'status' => 'pending',
            'payment_method' => 'manual_transfer',
            'payment_reference' => null,
            'paid_at' => null,
            'expires_at' => Carbon::now()->addHours(24),
        ]);

        OrderItem::create([
            'order_id' => $pendingOrder->id,
            'course_id' => $ekma4214->id,
            'price' => $ekma4214->price,
        ]);

        OrderItem::create([
            'order_id' => $pendingOrder->id,
            'course_id' => $hkum4101->id,
            'price' => $hkum4101->price,
        ]);

        // ------------------------------------------------------------------
        // Order #3 — status: expired (tamu / guest)
        // Masa pembayaran habis => tidak ada entitlement.
        // ------------------------------------------------------------------
        $expiredOrder = Order::create([
            'user_id' => null,
            'guest_name' => 'Andi Wijaya',
            'guest_email' => 'andi@example.com',
            'guest_phone' => '081234567890',
            'total_amount' => $sosi4201->price,
            'status' => 'expired',
            'payment_method' => 'finpay',
            'payment_reference' => null,
            'paid_at' => null,
            'expires_at' => Carbon::now()->subDays(5),
        ]);

        OrderItem::create([
            'order_id' => $expiredOrder->id,
            'course_id' => $sosi4201->id,
            'price' => $sosi4201->price,
        ]);
    }
}
