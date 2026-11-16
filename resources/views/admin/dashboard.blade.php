@extends('admin.layout')

@section('title', 'Dashboard')

@section('admin_content')
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900">Dashboard</h1>
            <p class="mt-1 text-sm text-slate-500">Ringkasan performa toko Anda.</p>
        </div>
        <span class="hidden rounded-full bg-white px-3 py-1.5 text-xs font-semibold text-slate-500 shadow-sm sm:block">
            {{ now()->translatedFormat('l, d F Y') }}
        </span>
    </div>

    <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6">
        @php
            $cards = [
                ['Pendapatan', 'Rp '.number_format($stats['revenue'], 0, ',', '.'), 'emerald', 'M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z'],
                ['Total Order', number_format($stats['orders'], 0, ',', '.'), 'blue', 'M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007Z'],
                ['Order Paid', number_format($stats['paid_orders'], 0, ',', '.'), 'teal', 'M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z'],
                ['Mata Kuliah', number_format($stats['courses'], 0, ',', '.'), 'violet', 'M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25'],
                ['Pengguna', number_format($stats['users'], 0, ',', '.'), 'amber', 'M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z'],
                ['Total Download', number_format($stats['downloads'], 0, ',', '.'), 'sky', 'M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3'],
            ];
        @endphp

        @foreach ($cards as [$label, $value, $color, $icon])
            @php
                $palette = [
                    'emerald' => 'bg-emerald-100 text-emerald-600',
                    'blue' => 'bg-blue-100 text-blue-600',
                    'teal' => 'bg-teal-100 text-teal-600',
                    'violet' => 'bg-violet-100 text-violet-600',
                    'amber' => 'bg-amber-100 text-amber-600',
                    'sky' => 'bg-sky-100 text-sky-600',
                ][$color];
            @endphp
            <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition-shadow hover:shadow-md">
                <div class="flex items-center justify-between">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">{{ $label }}</p>
                    <span class="flex h-9 w-9 items-center justify-center rounded-xl {{ $palette }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-5 w-5"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon }}" /></svg>
                    </span>
                </div>
                <p class="mt-2 text-2xl font-extrabold text-slate-900">{{ $value }}</p>
            </div>
        @endforeach
    </div>

    <div class="mt-6 grid gap-4 lg:grid-cols-3">
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm lg:col-span-2">
            <div class="flex items-center justify-between">
                <h2 class="font-bold text-slate-900">Pendapatan 14 Hari Terakhir</h2>
                <span class="text-xs font-semibold text-slate-400">Order Paid</span>
            </div>
            <div class="mt-4 h-64"><canvas id="revenueChart"></canvas></div>
        </div>
        <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
            <h2 class="font-bold text-slate-900">Status Order</h2>
            <div class="mt-4 flex h-64 items-center justify-center"><canvas id="statusChart"></canvas></div>
        </div>
    </div>
    <div class="mt-6 grid gap-4 lg:grid-cols-3">
        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
            <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
                <h2 class="font-bold text-slate-900">Mata Kuliah Terlaris</h2>
                <a href="{{ route('admin.courses.index') }}" class="text-xs font-semibold text-blue-600 hover:underline">Lihat semua</a>
            </div>
            <ul class="divide-y divide-slate-100">
                @forelse ($topCourses as $i => $course)
                    <li class="flex items-center gap-3 px-5 py-3">
                        <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg text-xs font-extrabold {{ $i === 0 ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-500' }}">{{ $i + 1 }}</span>
                        <div class="min-w-0 flex-1">
                            <p class="truncate text-sm font-semibold text-slate-800">{{ $course->code }}</p>
                            <p class="truncate text-xs text-slate-400">{{ $course->name }}</p>
                        </div>
                        <span class="text-sm font-bold text-slate-700">{{ $course->sales }} terjual</span>
                    </li>
                @empty
                    <li class="px-5 py-8 text-center text-sm text-slate-400">Belum ada penjualan.</li>
                @endforelse
            </ul>
        </div>

        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm lg:col-span-2">
            <div class="flex items-center justify-between border-b border-slate-100 px-5 py-4">
                <h2 class="font-bold text-slate-900">Order Terbaru</h2>
                <a href="{{ route('admin.orders.index') }}" class="text-xs font-semibold text-blue-600 hover:underline">Lihat semua</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-xs uppercase text-slate-400">
                        <tr>
                            <th class="px-5 py-3">ID</th>
                            <th class="px-5 py-3">Pembeli</th>
                            <th class="px-5 py-3">Total</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($recentOrders as $order)
                            <tr class="transition-colors hover:bg-slate-50">
                                <td class="px-5 py-3 font-semibold text-slate-700">#{{ $order->id }}</td>
                                <td class="px-5 py-3 text-slate-600">{{ $order->user?->name ?? $order->guest_name ?? '-' }}</td>
                                <td class="px-5 py-3 font-semibold text-slate-800">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                <td class="px-5 py-3">@include('admin.partials.status-badge', ['status' => $order->status])</td>
                                <td class="px-5 py-3 text-slate-400">{{ $order->created_at->format('d M Y') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-5 py-8 text-center text-slate-400">Belum ada order.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            new Chart(document.getElementById('revenueChart'), {
                type: 'bar',
                data: {
                    labels: @json($revenueLabels),
                    datasets: [{
                        label: 'Pendapatan',
                        data: @json($revenueData),
                        backgroundColor: 'rgba(59, 130, 246, 0.85)',
                        borderRadius: 6,
                        maxBarThickness: 28,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: { callbacks: { label: (ctx) => 'Rp ' + new Intl.NumberFormat('id-ID').format(ctx.raw) } }
                    },
                    scales: {
                        y: { beginAtZero: true, ticks: { callback: (v) => 'Rp ' + new Intl.NumberFormat('id-ID').format(v) }, grid: { color: 'rgba(148,163,184,0.12)' } },
                        x: { grid: { display: false } }
                    }
                }
            });

            const statusMap = { pending: ['Pending', '#f59e0b'], paid: ['Paid', '#10b981'], expired: ['Expired', '#94a3b8'], failed: ['Failed', '#ef4444'] };
            const statusData = @json($orderStatus);
            const labels = [], data = [], colors = [];
            Object.entries(statusMap).forEach(([key, [label, color]]) => { labels.push(label); data.push(statusData[key] || 0); colors.push(color); });

            new Chart(document.getElementById('statusChart'), {
                type: 'doughnut',
                data: { labels: labels, datasets: [{ data: data, backgroundColor: colors, borderWidth: 2, borderColor: '#fff' }] },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '68%',
                    plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, boxWidth: 8 } } }
                }
            });
        });
    </script>
@endpush


