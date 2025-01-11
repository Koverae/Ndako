<?php extract((new \Illuminate\Support\Collection($attributes->getAttributes()))->mapWithKeys(function ($value, $key) { return [Illuminate\Support\Str::camel(str_replace([':', '.'], ' ', $key)) => $value]; })->all(), EXTR_SKIP); ?>
@props(['value','status'])
<x-app::form.button.status-bar.simple :value="$value" :status="$status" >

{{ $slot ?? "" }}
</x-app::form.button.status-bar.simple>