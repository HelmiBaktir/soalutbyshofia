<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Facades\Http;

class DuitkuService
{
    /**
     * Buat invoice di Duitku dan kembalikan paymentUrl.
     *
     * @return array{payment_url: string, reference: ?string}
     */
    public function createInvoice(Order $order): array
    {
        $merchantCode = (string) config('duitku.merchant_code');
        $apiKey = (string) config('duitku.api_key');
        $amount = (int) $order->total_amount;
        $merchantOrderId = (string) $order->id;

        $order->load('items.course');

        $signature = md5($merchantCode.$merchantOrderId.$amount.$apiKey);

        $payload = [
            'merchantCode' => $merchantCode,
            'paymentAmount' => $amount,
            'paymentMethod' => 'VA',
            'merchantOrderId' => $merchantOrderId,
            'productDetails' => 'Bank Soal UT By Shofia',
            'email' => $order->user?->email ?? $order->guest_email,
            'phoneNumber' => $order->guest_phone ?? '081234567890',
            'customerVaName' => mb_substr($order->user?->name ?? $order->guest_name ?? 'Customer', 0, 20),
            'callbackUrl' => config('duitku.callback_url') ?: route('payment.duitku.callback'),
            'returnUrl' => config('duitku.return_url') ?: route('payment.success', ['order' => $order->id]),
            'signature' => $signature,
            'expiryPeriod' => 1440,
            'itemDetails' => $order->items->map(fn ($item) => [
                'name' => mb_substr($item->course->code.' - '.$item->course->name, 0, 50),
                'price' => (int) $item->price,
                'quantity' => 1,
            ])->values()->all(),
        ];

        $response = Http::timeout(30)
            ->asJson()
            ->post(rtrim((string) config('duitku.base_url'), '/').'/v2/inquiry', $payload);

        $data = $response->json() ?? [];

        if (! $response->successful() || ($data['statusCode'] ?? '') !== '00' || empty($data['paymentUrl'])) {
            throw new \RuntimeException('Gagal membuat pembayaran Duitku: '.($data['statusMessage'] ?? $response->body()));
        }

        $order->update(['payment_reference' => $data['reference'] ?? null]);

        return [
            'payment_url' => $data['paymentUrl'],
            'reference' => $data['reference'] ?? null,
        ];
    }

    /**
     * Verifikasi signature callback Duitku.
     * Format: md5(merchantCode + amount + merchantOrderId + apiKey).
     */
    public function verifyCallback(array $payload): bool
    {
        $signature = md5(
            (string) ($payload['merchantCode'] ?? '').
            (string) ($payload['amount'] ?? '').
            (string) ($payload['merchantOrderId'] ?? '').
            (string) config('duitku.api_key')
        );

        return hash_equals($signature, (string) ($payload['signature'] ?? ''));
    }

    /**
     * Cek status transaksi Duitku.
     * Format signature: md5(merchantCode + merchantOrderId + apiKey).
     */
    public function checkStatus(string $merchantOrderId): array
    {
        $merchantCode = (string) config('duitku.merchant_code');
        $signature = md5($merchantCode.$merchantOrderId.(string) config('duitku.api_key'));

        $response = Http::timeout(30)
            ->asJson()
            ->post(rtrim((string) config('duitku.base_url'), '/').'/transactionStatus', [
                'merchantCode' => $merchantCode,
                'merchantOrderId' => $merchantOrderId,
                'signature' => $signature,
            ]);

        return $response->json() ?? [];
    }
}
