<?php
use function Laravel\Folio\name;

name('slug.user');
?>

<x-layout.app>
    <div>
        <h1>slug.[User:slug] Route Model Binding</h1>
        @dump($user->toArray())
    </div>
</x-layout.app>
