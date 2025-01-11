@props([
    'value'
])
<div>
    {{-- {{ \Carbon\Carbon::parse($value)->locale('fr')->isoFormat('LL LTS') }} --}}
    {{ \Carbon\Carbon::parse($value)->format('d M Y') }}
</div>
