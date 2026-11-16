@php
    $map = [
        'pending' => ['bg-amber-100 text-amber-700', 'Pending'],
        'paid' => ['bg-emerald-100 text-emerald-700', 'Paid'],
        'expired' => ['bg-slate-200 text-slate-600', 'Expired'],
        'failed' => ['bg-red-100 text-red-700', 'Failed'],
    ];
    [$classes, $label] = $map[$status] ?? ['bg-slate-100 text-slate-500', ucfirst($status)];
@endphp
<span class="inline-flex items-center whitespace-nowrap rounded-full px-2.5 py-0.5 text-xs font-semibold {{ $classes }}">
    {{ $label }}
</span>
