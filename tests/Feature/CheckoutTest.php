<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Entitlement;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    private static int $courseCounter = 0;

    private function makeCourse(int $price = 100000): Course
    {
        self::$courseCounter++;

        return Course::create([
            'code' => 'TEST'.str_pad((string) self::$courseCounter, 4, '0', STR_PAD_LEFT),
            'name' => 'Mata Kuliah Test '.self::$courseCounter,
            'prodi' => 'Prodi Test',
            'semester' => '1',
            'price' => $price,
        ]);
    }

    public function test_checkout_creates_a_pending_order(): void
    {
        $course = $this->makeCourse();

        $response = $this->post('/checkout', [
            'course_ids' => [$course->id],
            'name' => 'Guest Test',
            'email' => 'guest@example.com',
            'phone' => '08123456789',
            'payment_method' => 'finpay',
        ]);

        $response->assertRedirect();

        $this->assertDatabaseHas('orders', [
            'status' => 'pending',
            'guest_email' => 'guest@example.com',
            'total_amount' => $course->price,
        ]);

        $order = Order::where('guest_email', 'guest@example.com')->firstOrFail();

        $this->assertDatabaseHas('order_items', [
            'order_id' => $order->id,
            'course_id' => $course->id,
            'price' => $course->price,
        ]);
    }

    public function test_finpay_callback_success_creates_entitlement_and_marks_order_paid(): void
    {
        $course = $this->makeCourse();
        $user = User::factory()->create();

        $order = Order::create([
            'user_id' => $user->id,
            'total_amount' => $course->price,
            'status' => 'pending',
            'payment_method' => 'finpay',
        ]);

        $order->items()->create([
            'course_id' => $course->id,
            'price' => $course->price,
        ]);

        $response = $this->postJson('/finpay/callback', [
            'order_id' => $order->id,
            'status' => 'paid',
            'payment_reference' => 'REF-TEST-123',
        ]);

        $response->assertOk();

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'status' => 'paid',
            'payment_reference' => 'REF-TEST-123',
        ]);

        $this->assertDatabaseHas('entitlements', [
            'order_id' => $order->id,
            'course_id' => $course->id,
            'user_id' => $user->id,
        ]);

        $entitlement = Entitlement::where('order_id', $order->id)->first();

        $this->assertNotNull($entitlement->download_expires_at);
        $this->assertTrue($entitlement->download_expires_at->isFuture());
    }

    public function test_ninth_course_checkout_is_rejected_for_a_user(): void
    {
        $user = User::factory()->create();

        // Pengguna sudah memiliki 8 mata kuliah berbeda.
        $owned = collect();
        for ($i = 0; $i < 8; $i++) {
            $owned->push($this->makeCourse());
        }

        $order = Order::create([
            'user_id' => $user->id,
            'total_amount' => $owned->sum('price'),
            'status' => 'paid',
            'payment_method' => 'finpay',
        ]);

        foreach ($owned as $course) {
            $order->items()->create([
                'course_id' => $course->id,
                'price' => $course->price,
            ]);

            Entitlement::create([
                'user_id' => $user->id,
                'order_id' => $order->id,
                'course_id' => $course->id,
                'granted_at' => now(),
                'download_expires_at' => now()->addMonths(3),
            ]);
        }

        // Mata kuliah ke-9.
        $newCourse = $this->makeCourse();

        $response = $this->actingAs($user)->post('/checkout', [
            'course_ids' => [$newCourse->id],
            'payment_method' => 'finpay',
        ]);

        $response->assertSessionHasErrors('courses');
    }
}
