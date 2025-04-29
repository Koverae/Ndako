@props([
    'value',
])
@php
    $category = \Modules\RevenueManager\Models\Expenses\ExpenseCategory::find($value);
@endphp
<div>
    {{ $category->name }}
</div>
