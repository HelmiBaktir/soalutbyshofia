@props(['column', 'label'])

@php
    $params = request()->except(['sort', 'direction', 'page']);
    $params['sort'] = $column;
    $params['direction'] = request('sort') === $column && request('direction') !== 'desc' ? 'desc' : 'asc';
    $isActive = request('sort') === $column;
    $arrow = $isActive ? (request('direction') === 'desc' ? '↓' : '↑') : '↕';
@endphp

<a href="{{ url()->current().'?'.http_build_query($params) }}"
    class="inline-flex items-center gap-1 transition-colors hover:text-slate-700 {{ $isActive ? 'text-blue-600' : 'text-slate-500' }}">
    {{ $label }}
    <span class="{{ $isActive ? 'text-blue-500' : 'text-slate-300' }}">{{ $arrow }}</span>
</a>
