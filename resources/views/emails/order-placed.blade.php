<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Order Baru</title>
</head>
<body style="font-family: Arial, sans-serif; color: #1f2937; line-height: 1.6;">
    <h1>Ada Order Baru!</h1>

    <p>Order baru masuk dan perlu diproses:</p>

    <p>
        <strong>Order #{{ $order->id }}</strong><br>
        <strong>Waktu:</strong> {{ $order->created_at->format('d M Y, H:i') }}
    </p>

    <p>
        <strong>Pembeli:</strong> {{ $order->user?->name ?? $order->guest_name ?? '-' }}<br>
        <strong>Email:</strong> {{ $order->user?->email ?? $order->guest_email ?? '-' }}<br>
        <strong>Metode:</strong> {{ str_replace('_', ' ', $order->payment_method ?? '-') }}
    </p>

    <h3>Rincian Pesanan</h3>
    <ul>
        @foreach ($order->items as $item)
            <li>{{ $item->course->code }} &mdash; {{ $item->course->name }} (Rp {{ number_format($item->price, 0, ',', '.') }})</li>
        @endforeach
    </ul>

    <p><strong>Total:</strong> Rp {{ number_format($order->total_amount, 0, ',', '.') }}</p>

    <p>Segera buka panel admin untuk memverifikasi pembayaran.</p>
</body>
</html>
