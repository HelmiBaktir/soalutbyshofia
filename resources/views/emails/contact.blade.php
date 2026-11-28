<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Pesan Kontak</title>
</head>
<body style="font-family: Arial, sans-serif; color: #1f2937; line-height: 1.6;">
    <h1>Pesan Kontak Baru</h1>

    <p><strong>Nama:</strong> {{ $data['name'] }}</p>
    <p><strong>Email:</strong> {{ $data['email'] }}</p>
    <p><strong>Pesan:</strong></p>
    <p style="white-space: pre-wrap; background: #f8fafc; padding: 12px; border-radius: 8px;">{{ $data['message'] }}</p>
</body>
</html>
