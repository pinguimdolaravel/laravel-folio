@php

    $user = \App\Models\User::withTrashed()->find($id);

@endphp

<x-layout.app>
    <div>
        <h1>user.id</h1>
        <h2>{{ $id }}</h2>
        <h2>{{ $user->name }}</h2>
    </div>
</x-layout.app>
