<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Pembayaran Berhasil</title>
</head>
<body style="font-family: Arial, sans-serif; color: #1f2937; line-height: 1.6;">
    <h1>Pembayaran Berhasil</h1>

    <p>Halo {{ $order->user?->name ?? $order->guest_name }},</p>
    <p>Terima kasih, pembayaran Anda untuk order <strong>#{{ $order->id }}</strong> telah kami terima.</p>

    <h3>Rincian Pesanan</h3>
    <ul>
        @foreach ($order->items as $item)
            <li>{{ $item->course->code }} &mdash; {{ $item->course->name }} (Rp {{ number_format($item->price, 0, ',', '.') }})</li>
        @endforeach
    </ul>

    <p><strong>Total:</strong> Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>

    <p>Akses download tersedia selama 3 bulan sejak pembayaran.</p>
</body>
</html>
