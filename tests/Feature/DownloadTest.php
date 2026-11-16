<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Entitlement;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DownloadTest extends TestCase
{
    use RefreshDatabase;

    private static int $courseCounter = 0;

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    private function makeCourse(): Course
    {
        self::$courseCounter++;

        return Course::create([
            'code' => 'TEST'.str_pad((string) self::$courseCounter, 4, '0', STR_PAD_LEFT),
            'name' => 'Mata Kuliah Test '.self::$courseCounter,
            'prodi' => 'Prodi Test',
            'semester' => '1',
            'price' => 50000,
        ]);
    }

    public function test_user_without_entitlement_cannot_download(): void
    {
        $user = User::factory()->create();
        $course = $this->makeCourse();

        $response = $this->actingAs($user)->get(route('course.download', $course));

        $response->assertForbidden();
    }

    public function test_download_is_rejected_after_three_months(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-01-01 00:00:00'));

        $user = User::factory()->create();
        $course = $this->makeCourse();

        $order = Order::create([
            'user_id' => $user->id,
            'total_amount' => $course->price,
            'status' => 'paid',
            'payment_method' => 'finpay',
            'paid_at' => now(),
        ]);

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

        // Maju 4 bulan -> masa download (3 bulan) sudah lewat.
        Carbon::setTestNow(now()->addMonths(4));

        $response = $this->actingAs($user)->get(route('course.download', $course));

        $response->assertForbidden();
    }
}
