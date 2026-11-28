<?php

namespace App\Http\Controllers;

use App\Mail\OrderPlacedMail;
use App\Mail\PaymentSuccessMail;
use App\Models\Order;
use App\Services\CheckoutService;
use App\Services\DuitkuService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PaymentController extends Controller
{
    public function __construct(
        private readonly CheckoutService $checkout,
        private readonly DuitkuService $duitku,
    ) {
    }

    /**
     * Proses checkout: buat order pending, lalu arahkan ke Duitku.
     */
    public function checkout(Request $request): RedirectResponse
    {
        $rules = [
            'course_ids' => ['required', 'array', 'min:1'],
            'course_ids.*' => ['required', 'integer', 'exists:courses,id'],
            'payment_method' => ['required', 'in:duitku'],
        ];

        // Guest checkout wajib menyertakan data kontak.
        if (! $request->user()) {
            $rules['name'] = ['required', 'string', 'max:255'];
            $rules['email'] = ['required', 'email', 'max:255'];
            $rules['phone'] = ['required', 'string', 'max:30'];
        }

        $data = $request->validate($rules);

        $guest = [
            'name' => $data['name'] ?? null,
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'] ?? null,
            'payment_method' => $data['payment_method'],
        ];

        $order = $this->checkout->createOrder($request->user(), $guest, $data['course_ids']);

        // Notifikasi ke pemilik saat ada order baru masuk.
        if ($notifyEmail = config('app.order_notification_email')) {
            try {
                Mail::to($notifyEmail)->send(new OrderPlacedMail($order));
            } catch (\Throwable $e) {
                report($e);
            }
        }

        $result = $this->duitku->createInvoice($order);

        return redirect()->to($result['payment_url']);
    }

    /**
     * Callback / webhook dari Duitku.
     */
    public function duitkuCallback(Request $request): JsonResponse
    {
        $payload = $request->all();

        if (! $this->duitku->verifyCallback($payload)) {
            return response()->json(['status' => 'error', 'message' => 'Invalid signature'], 401);
        }

        $order = Order::find($payload['merchantOrderId'] ?? null);

        if (! $order) {
            return response()->json(['status' => 'error', 'message' => 'Order tidak ditemukan'], 404);
        }

        $resultCode = (string) ($payload['resultCode'] ?? '');

        // 00 = sukses, 02 = gagal/batal.
        if ($resultCode === '00') {
            $this->handleSuccess($order, $payload['reference'] ?? null);
        } elseif ($resultCode === '02') {
            $this->checkout->markAsFailed($order);
        }

        return response()->json(['status' => 'ok']);
    }

    /**
     * Halaman sukses pembayaran.
     */
    public function success(Request $request)
    {
        $order = Order::with('items.course', 'entitlements.course')->find($request->integer('order'));

        return view('payment.success', compact('order'));
    }

    private function handleSuccess(Order $order, ?string $reference): void
    {
        $this->checkout->markAsPaid($order, $reference);

        $email = $order->user?->email ?? $order->guest_email;

        if ($email) {
            try {
                Mail::to($email)->send(new PaymentSuccessMail($order));
            } catch (\Throwable $e) {
                report($e);
            }
        }
    }
}
