<?php

namespace App\Services;

use App\Models\Course;
use App\Models\Entitlement;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

class CheckoutService
{
    /**
     * Batas maksimal mata kuliah yang boleh dimiliki satu akun.
     */
    public const MAX_COURSES_PER_ACCOUNT = 8;

    /**
     * Buat order berstatus pending beserta order_items.
     *
     * @param  array{name: ?string, email: ?string, phone: ?string, payment_method: string}  $guest
     * @param  array<int>  $courseIds
     */
    public function createOrder(?User $user, array $guest, array $courseIds): Order
    {
        $courseIds = collect($courseIds)
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->filter()
            ->values();

        if ($courseIds->isEmpty()) {
            throw ValidationException::withMessages(['courses' => 'Pilih minimal satu mata kuliah.']);
        }

        $courses = Course::whereIn('id', $courseIds)->get();

        if ($courses->count() !== $courseIds->count()) {
            throw ValidationException::withMessages(['courses' => 'Terdapat mata kuliah yang tidak valid.']);
        }

        if ($user) {
            $this->assertWithinPurchaseLimit($user, $courseIds->all());
        }

        $order = Order::create([
            'user_id' => $user?->id,
            'guest_name' => $user ? null : ($guest['name'] ?? null),
            'guest_email' => $user ? null : ($guest['email'] ?? null),
            'guest_phone' => $user ? null : ($guest['phone'] ?? null),
            'total_amount' => $courses->sum('price'),
            'status' => 'pending',
            'payment_method' => $guest['payment_method'] ?? 'finpay',
            'expires_at' => now()->addDay(),
        ]);

        foreach ($courses as $course) {
            $order->items()->create([
                'course_id' => $course->id,
                'price' => $course->price,
            ]);
        }

        return $order->refresh();
    }

    /**
     * Tandai order sebagai paid dan berikan entitlement ke setiap item.
     */
    public function markAsPaid(Order $order, ?string $paymentReference = null, ?Carbon $paidAt = null): Order
    {
        $paidAt ??= now();

        $order->update([
            'status' => 'paid',
            'payment_reference' => $paymentReference ?? $order->payment_reference,
            'paid_at' => $paidAt,
        ]);

        foreach ($order->items as $item) {
            Entitlement::firstOrCreate(
                [
                    'order_id' => $order->id,
                    'course_id' => $item->course_id,
                ],
                [
                    'user_id' => $order->user_id,
                    'granted_at' => $paidAt,
                    'download_expires_at' => $paidAt->copy()->addMonths(3),
                ]
            );
        }

        return $order->refresh();
    }

    public function markAsFailed(Order $order): Order
    {
        $order->update(['status' => 'failed']);

        return $order->refresh();
    }

    public function markAsExpired(Order $order): Order
    {
        $order->update(['status' => 'expired']);

        return $order->refresh();
    }

    /**
     * Validasi batas maksimal 8 mata kuliah per akun.
     *
     * @param  array<int>  $courseIds
     */
    public function assertWithinPurchaseLimit(User $user, array $courseIds): void
    {
        $ownedIds = $user->entitlements()->pluck('course_id')->unique();

        $union = $ownedIds->merge($courseIds)->unique();

        if ($union->count() > self::MAX_COURSES_PER_ACCOUNT) {
            throw ValidationException::withMessages([
                'courses' => 'Maksimal '.self::MAX_COURSES_PER_ACCOUNT.' mata kuliah per akun.',
            ]);
        }
    }
}
