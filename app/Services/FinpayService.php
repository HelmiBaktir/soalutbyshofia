<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Str;

/**
 * Scaffold integrasi FinPay.
 *
 * Pemanggilan HTTP asli FinPay ditandai TODO di bawah. Saat kredensial merchant
 * FinPay dan kontrak API resmi sudah tersedia, lengkapi method ini — atau ganti
 * dengan package `rublex/laravel-finpay-gateway` bila sudah siap dipakai.
 */
class FinpayService
{
    /**
     * Buat transaksi pembayaran di FinPay dan kembalikan redirect_url.
     *
     * @return array{redirect_url: string, payment_reference: string}
     */
    public function initiatePayment(Order $order): array
    {
        // TODO: Panggil API FinPay untuk membuat transaksi.
        //
        // $response = Http::timeout(config('finpay.timeout'))
        //     ->asJson()
        //     ->post(
        //         rtrim((string) config('finpay.base_url'), '/').(string) config('finpay.payment_endpoint'),
        //         $this->buildPaymentPayload($order)
        //     );
        //
        // return [
        //     'redirect_url' => $response->json('redirect_url'),
        //     'payment_reference' => $response->json('payment_reference'),
        // ];

        $reference = $order->payment_reference
            ?: 'FINPAY-'.Str::upper(Str::random(12));

        $order->update(['payment_reference' => $reference]);

        return [
            'redirect_url' => config('finpay.redirect_url')
                ?: route('payment.success', ['order' => $order->id]),
            'payment_reference' => $reference,
        ];
    }

    /**
     * Verifikasi callback FinPay dan normalisasi status.
     *
     * @return array{order_id: int|string|null, status: string, payment_reference: ?string}
     */
    public function verifyPayment(array $payload): array
    {
        // TODO: verifikasi signature callback menggunakan merchant key/secret FinPay.
        // if (! $this->isValidSignature($payload)) { abort(401, 'Invalid signature'); }

        $orderId = $payload['order_id']
            ?? $payload['orderId']
            ?? $payload['reference']
            ?? null;

        $status = strtolower((string) ($payload['status'] ?? $payload['transaction_status'] ?? 'pending'));

        $successStatuses = ['success', 'paid', 'completed', 'settlement', 'capture', 'sukses'];
        $failedStatuses = ['failed', 'denied', 'cancel', 'canceled', 'gagal'];
        $expiredStatuses = ['expired', 'expire', 'kadaluarsa'];

        $mapped = 'pending';

        if (in_array($status, $successStatuses, true)) {
            $mapped = 'success';
        } elseif (in_array($status, $failedStatuses, true)) {
            $mapped = 'failed';
        } elseif (in_array($status, $expiredStatuses, true)) {
            $mapped = 'expired';
        }

        return [
            'order_id' => $orderId,
            'status' => $mapped,
            'payment_reference' => $payload['payment_reference'] ?? $payload['reference'] ?? null,
        ];
    }

    /**
     * Susun payload pembayaran FinPay.
     *
     * @return array<string, mixed>
     */
    protected function buildPaymentPayload(Order $order): array
    {
        return [
            'merchant_id' => config('finpay.merchant_id'),
            'order_id' => (string) $order->id,
            'amount' => $order->total_amount,
            'customer_name' => $order->user?->name ?? $order->guest_name,
            'customer_email' => $order->user?->email ?? $order->guest_email,
            'customer_phone' => $order->guest_phone,
            'callback_url' => config('finpay.callback_url') ?: route('finpay.callback'),
            'redirect_url' => config('finpay.redirect_url') ?: route('payment.success', ['order' => $order->id]),
            'signature' => $this->generateSignature($order),
        ];
    }

    /**
     * Generate signature (scaffold).
     *
     * TODO: sesuaikan dengan algoritma signature resmi FinPay.
     */
    protected function generateSignature(Order $order): string
    {
        $merchantKey = (string) config('finpay.merchant_key', '');

        return hash_hmac('sha256', (string) $order->id.$order->total_amount, $merchantKey);
    }
}
