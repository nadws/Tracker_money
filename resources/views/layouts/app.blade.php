<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-[#f4fbf7]">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ isset($title) ? $title . ' - ' : '' }}AkuntingPro</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased text-slate-900 h-full selection:bg-emerald-500 selection:text-white">
    <div class="min-h-screen bg-[#f4fbf7]">
        <div class="sticky top-0 z-40 border-b border-emerald-100/80 bg-white/85 backdrop-blur-xl">
            @include('layouts.navigation')
        </div>

        @isset($header)
            <header class="border-b border-emerald-100 bg-white/70 py-5">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center justify-between">
                        <h1 class="text-xl font-extrabold text-slate-900 tracking-tight">
                            {{ $header }}
                        </h1>
                    </div>
                </div>
            </header>
        @endisset

        <main class="py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                {{ $slot }}
            </div>
        </main>
    </div>
</body>

</html>
