<?php extract((new \Illuminate\Support\Collection($attributes->getAttributes()))->mapWithKeys(function ($value, $key) { return [Illuminate\Support\Str::camel(str_replace([':', '.'], ' ', $key)) => $value]; })->all(), EXTR_SKIP); ?>
@props(['value','data'])
<x-app::blocks.boxes.input.simple :value="$value" :data="$data" >

{{ $slot ?? "" }}
</x-app::blocks.boxes.input.simple>