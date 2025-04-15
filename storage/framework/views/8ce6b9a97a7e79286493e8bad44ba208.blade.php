<?php extract((new \Illuminate\Support\Collection($attributes->getAttributes()))->mapWithKeys(function ($value, $key) { return [Illuminate\Support\Str::camel(str_replace([':', '.'], ' ', $key)) => $value]; })->all(), EXTR_SKIP); ?>
@props(['value','model','key','id'])
<x-app::table.card.simple :value="$value" :model="$model" :key="$key" :id="$id" >

{{ $slot ?? "" }}
</x-app::table.card.simple>