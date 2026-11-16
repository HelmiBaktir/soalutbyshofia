<?php

namespace App\Http\Controllers;

use App\Mail\PaymentSuccessMail;
use App\Models\Order;
use App\Services\CheckoutService;
use App\Services\FinpayService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PaymentController extends Controller
{
    public function __construct(
        private readonly CheckoutService $checkout,
        private readonly FinpayService $finpay,
    ) {
    }

    /**
     * Proses checkout: buat order pending, lalu arahkan ke FinPay.
     */
    public function checkout(Request $request): RedirectResponse
    {
        $rules = [
            'course_ids' => ['required', 'array', 'min:1'],
            'course_ids.*' => ['required', 'integer', 'exists:courses,id'],
            'payment_method' => ['required', 'in:finpay,manual_transfer'],
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

        $result = $this->finpay->initiatePayment($order);

        return redirect()->to($result['redirect_url']);
    }

    /**
     * Callback / webhook dari FinPay.
     */
    public function callback(Request $request): JsonResponse
    {
        $result = $this->finpay->verifyPayment($request->all());

        $order = Order::find($result['order_id']);

        if (! $order && $result['payment_reference']) {
            $order = Order::where('payment_reference', $result['payment_reference'])->first();
        }

        if (! $order) {
            return response()->json(['status' => 'error', 'message' => 'Order tidak ditemukan.'], 404);
        }

        if ($result['status'] === 'success') {
            $this->handleSuccess($order, $result['payment_reference']);
        } elseif ($result['status'] === 'failed') {
            $this->checkout->markAsFailed($order);
        } elseif ($result['status'] === 'expired') {
            $this->checkout->markAsExpired($order);
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
            Mail::to($email)->send(new PaymentSuccessMail($order));
        }
    }
}
