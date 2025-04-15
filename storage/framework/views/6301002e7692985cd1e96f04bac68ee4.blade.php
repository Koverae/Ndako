<?php extract((new \Illuminate\Support\Collection($attributes->getAttributes()))->mapWithKeys(function ($value, $key) { return [Illuminate\Support\Str::camel(str_replace([':', '.'], ' ', $key)) => $value]; })->all(), EXTR_SKIP); ?>
@props(['value'])
<x-app::wizard.step-page.special.property.add-units :value="$value" >

{{ $slot ?? "" }}
</x-app::wizard.step-page.special.property.add-units>