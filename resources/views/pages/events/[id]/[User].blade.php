<?php

use function Laravel\Folio\middleware;

\Laravel\Folio\withTrashed();

?>

<x-layout.app>
    <div>
        <h1>model.[User] Route Model Binding</h1>
        <h2>{{ $id }}</h2>
        @dump($user->toArray())
    </div>
</x-layout.app>
