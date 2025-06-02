<?php extract((new \Illuminate\Support\Collection($attributes->getAttributes()))->mapWithKeys(function ($value, $key) { return [Illuminate\Support\Str::camel(str_replace([':', '.'], ' ', $key)) => $value]; })->all(), EXTR_SKIP); ?>
@props(['data','value'])
<x-app::form.input.simple :data="$data" :value="$value" >

{{ $slot ?? "" }}
</x-app::form.input.simple>