@php

    $users = \App\Models\User::all();

@endphp

<x-layout.app>
    <div>
        <h1>user.index</h1>

        <ul>
            @foreach($users as $user)

                <li>{{ $user->name }}</li>

            @endforeach
        </ul>
    </div>
</x-layout.app>
