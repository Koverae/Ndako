@props([
    'value',
])
@php
    $table = \Modules\Pos\Models\Floor\Table::find($value) ?? null;
@endphp
<div>
    @if($table)
    <a href="#">
        {{ $table->floor->name }} - {{ $table->table_name }}
    </a>
    @else
    <a href="#">
        {{ __('Direct Sale') }}
    </a>
    @endif
</div>
