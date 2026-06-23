<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Tracker Keuangan</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased text-slate-900 h-full selection:bg-indigo-500 selection:text-white">
    <div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100/50">
        <div class="sticky top-0 z-40 backdrop-blur-md bg-white/80 border-b border-slate-200/80">
            @include('layouts.navigation')
        </div>

        @isset($header)
            <header class="bg-white border-b border-slate-100 py-6 shadow-sm shadow-slate-100/40">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center justify-between">
                        <h1 class="text-xl font-bold text-slate-800 tracking-tight">
                            {{ $header }}
                        </h1>
                    </div>
                </div>
            </header>
        @endisset

        <main class="py-10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                {{ $slot }}
            </div>
        </main>
    </div>
</body>

</html>
