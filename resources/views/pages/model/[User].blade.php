<?php

use function Laravel\Folio\middleware;

middleware('auth');

?>

<x-layout.app>
    <div>
        <h1>model.[User] Route Model Binding</h1>
        @dump($user->toArray())
    </div>
</x-layout.app>
