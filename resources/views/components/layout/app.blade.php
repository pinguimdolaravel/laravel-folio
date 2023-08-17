@php
    $folio = app()->make(\Laravel\Folio\FolioManager::class);
    $mountPaths = $folio->mountPaths();
    $routes = collect($mountPaths)
        ->map(function(\Laravel\Folio\MountPath $mountPath) {
                $views = \Symfony\Component\Finder\Finder::create()->in($mountPath->path)->name('*.blade.php')->files()->getIterator();

            $domain = $mountPath->domain;
            $mountPath = str_replace(DIRECTORY_SEPARATOR, '/', $mountPath->path);

            $path = '/'.ltrim($mountPath, '/');
            return collect($views)
               ->map(function (SplFileInfo $view) use ($domain, $mountPath) {
            $viewPath = str_replace(DIRECTORY_SEPARATOR, '/', $view->getRealPath());
            $uri = str_replace($mountPath, '', $viewPath);

            $uri = str_replace('.blade.php', '', $uri);

            $uri = collect(explode('/', $uri))
                ->map(function (string $currentSegment) {
                    if (\Illuminate\Support\Str::startsWith($currentSegment, '[...')) {
                        $formattedSegment = '[...';
                    } elseif (\Illuminate\Support\Str::startsWith($currentSegment, '[')) {
                        $formattedSegment = '[';
                    } else {
                        return $currentSegment;
                    }

                    $lastPartOfSegment = str($currentSegment)->whenContains(
                        '.',
                        fn (Stringable $string) => $string->afterLast('.'),
                        fn (Stringable $string) => $string->afterLast('['),
                    );

                    return $formattedSegment.match (true) {
                        $lastPartOfSegment->contains(':') => $lastPartOfSegment->beforeLast(':')->camel()
                            .':'.$lastPartOfSegment->afterLast(':'),
                        $lastPartOfSegment->contains('-') => $lastPartOfSegment->beforeLast('-')->camel()
                            .':'.$lastPartOfSegment->afterLast('-'),
                        default => $lastPartOfSegment->camel(),
                    };
                })
                ->implode('/');

            $uri = preg_replace_callback('/\[(.*?)\]/', function (array $matches) {
                return '{'.\Illuminate\Support\Str::camel($matches[1]).'}';
            }, $uri);

            $uri = str_replace(['/index', '/index/'], ['', '/'], $uri);

            return [
                'name' => '',
                'uri' => $uri === '' ? '/' : $uri,
             ];
            });
        })->first();
@endphp

    <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com"></script>


    <!-- Styles -->
    <style>
        .bg-dots-darker {
            background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(0,0,0,0.07)'/%3E%3C/svg%3E")
        }

        .dark\:bg-dots-lighter {
            background-image: url("data:image/svg+xml,%3Csvg width='30' height='30' viewBox='0 0 30 30' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1.22676 0C1.91374 0 2.45351 0.539773 2.45351 1.22676C2.45351 1.91374 1.91374 2.45351 1.22676 2.45351C0.539773 2.45351 0 1.91374 0 1.22676C0 0.539773 0.539773 0 1.22676 0Z' fill='rgba(255,255,255,0.07)'/%3E%3C/svg%3E")
        }
    </style>
</head>
<body class="antialiased">
<ul class="px-4 py-8 space-y-2
    relative bg-dots-darker bg-center
    bg-gray-100 dark:bg-dots-lighter dark:bg-gray-900 selection:bg-red-500 selection:text-white dark:text-white
    ">
    @foreach($routes as $route)
        <li>
            <a class="text-blue-500 hover:underline hover:text-blue-700"
               href="{{ $route['uri'] }}">{{ $route['uri'] }}</a>
        </li>
    @endforeach
</ul>
<div
    class="
        relative sm:flex sm:justify-center sm:items-center min-h-screen
        bg-dots-darker bg-center bg-gray-100 dark:bg-dots-lighter dark:bg-gray-900
        selection:bg-red-500 selection:text-white dark:text-white
        ">
    {{ $slot }}
</div>
</body>
</html>
