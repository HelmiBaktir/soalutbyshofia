@extends('admin.layout')

@section('title', 'Order')

@section('admin_content')
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900">Order</h1>
            <p class="mt-1 text-sm text-slate-500">{{ $orders->total() }} order.</p>
        </div>
    </div>

    <form method="GET" action="{{ route('admin.orders.index') }}" class="mt-5 space-y-3">
        <div class="flex flex-wrap items-center gap-2">
            <input type="search" name="search" value="{{ request('search') }}" placeholder="Cari ID / pembeli / email..." class="w-72 rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm outline-none transition-all focus:border-blue-400 focus:ring-2 focus:ring-blue-100">
            <label class="flex items-center gap-1 text-sm text-slate-500">
                <span>Dari</span>
                <input type="date" name="from" value="{{ request('from') }}" class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm outline-none focus:border-blue-400">
            </label>
            <label class="flex items-center gap-1 text-sm text-slate-500">
                <span>s/d</span>
                <input type="date" name="to" value="{{ request('to') }}" class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm outline-none focus:border-blue-400">
            </label>
            <button type="submit" class="rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition-all hover:bg-blue-700">Filter</button>
            @if (request()->filled('search') || request()->filled('from') || request()->filled('to') || request()->filled('status'))
                <a href="{{ route('admin.orders.index') }}" class="rounded-xl px-3 py-2 text-sm font-semibold text-slate-500 transition-colors hover:bg-slate-100">Reset</a>
            @endif
        </div>

        <div class="flex flex-wrap gap-2">
            @php($statuses = [null => 'Semua', 'pending' => 'Pending', 'paid' => 'Paid', 'expired' => 'Expired', 'failed' => 'Failed'])
            @foreach ($statuses as $key => $label)
                <a href="{{ route('admin.orders.index', array_merge(request()->except('status', 'page'), $key ? ['status' => $key] : [])) }}"
                    class="rounded-full px-4 py-1.5 text-sm font-semibold transition-all {{ (request('status') === $key) || ($key === null && ! request('status')) ? 'bg-blue-600 text-white' : 'border border-slate-200 bg-white text-slate-600 hover:bg-slate-50' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>
    </form>
    <div class="mt-6 overflow-x-auto rounded-2xl border border-slate-200 bg-white shadow-sm">
        <table class="w-full text-left text-sm">
            <thead class="bg-slate-50 text-xs uppercase text-slate-400">
                <tr>
                    <th class="px-4 py-3">No</th>
                    <th class="px-4 py-3">Pembeli</th>
                    <th class="px-4 py-3">Metode</th>
                    <th class="px-4 py-3">Item</th>
                    <th class="px-4 py-3"><x-sort-link column="total_amount" label="Total" /></th>
                    <th class="px-4 py-3"><x-sort-link column="status" label="Status" /></th>
                    <th class="px-4 py-3"><x-sort-link column="created_at" label="Tanggal" /></th>
                    <th class="px-4 py-3 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse ($orders as $order)
                    <tr class="transition-colors hover:bg-slate-50">
                        <td class="px-4 py-3 font-semibold text-slate-500">{{ $loop->iteration + (($orders->currentPage() - 1) * $orders->perPage()) }}</td>
                        <td class="px-4 py-3">
                            <p class="font-semibold text-slate-800">{{ $order->user?->name ?? $order->guest_name ?? '-' }}</p>
                            <p class="text-xs text-slate-400">{{ $order->user?->email ?? $order->guest_email ?? '' }}</p>
                        </td>
                        <td class="px-4 py-3 text-slate-500">{{ str_replace('_', ' ', $order->payment_method ?? '-') }}</td>
                        <td class="px-4 py-3"><span class="rounded-full bg-slate-100 px-2 py-0.5 text-xs font-bold text-slate-600">{{ $order->items_count }}</span></td>
                        <td class="px-4 py-3 font-semibold text-slate-800">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                        <td class="px-4 py-3">@include('admin.partials.status-badge', ['status' => $order->status])</td>
                        <td class="px-4 py-3 text-slate-400">{{ $order->created_at->format('d M Y, H:i') }}</td>
                        <td class="px-4 py-3">
                            <div class="flex items-center justify-end gap-1">
                                @if ($order->status !== 'paid')
                                    <form method="POST" action="{{ route('admin.orders.confirm', $order) }}">
                                        @csrf
                                        <button type="submit" title="Konfirmasi Manual" class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 bg-white text-emerald-600 shadow-sm transition-colors hover:border-emerald-200 hover:bg-emerald-50">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" /></svg>
                                        </button>
                                    </form>
                                @endif
                                <a href="{{ route('admin.orders.show', $order) }}" title="Detail" class="inline-flex h-9 w-9 items-center justify-center rounded-lg border border-slate-200 bg-white text-blue-600 shadow-sm transition-colors hover:border-blue-200 hover:bg-blue-50">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-4 w-4"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" /></svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="px-4 py-12 text-center text-slate-400">Tidak ada order yang cocok.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">{{ $orders->links() }}</div>
@endsection

