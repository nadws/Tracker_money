<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ isset($title) ? $title . ' - ' : '' }}AkuntingPro</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-slate-900 antialiased">
    <main class="min-h-screen bg-[#f4fbf7]">
        <div class="mx-auto grid min-h-screen max-w-7xl lg:grid-cols-[1.05fr_.95fr]">
            <section class="hidden bg-emerald-700 px-10 py-12 text-white lg:flex lg:flex-col lg:justify-between">
                <a href="/" class="flex items-center gap-3">
                    <x-application-logo class="h-11 w-11 text-emerald-500" />
                    <span class="text-xl font-extrabold tracking-tight">AkuntingPro</span>
                </a>

                <div class="max-w-xl">
                    <p class="inline-flex rounded-full bg-white/15 px-4 py-2 text-sm font-bold text-emerald-50">
                        Catatan kas untuk usaha yang tumbuh
                    </p>
                    <h1 class="mt-6 text-5xl font-extrabold leading-tight tracking-tight">
                        Keuangan lebih tenang, keputusan lebih jelas.
                    </h1>
                    <p class="mt-5 text-lg leading-8 text-emerald-50/85">
                        Pantau pemasukan, pengeluaran, dan saldo kas harian dalam tampilan yang ringan, bersih, dan mudah dipahami.
                    </p>
                </div>

                <div class="grid gap-3">
                    <div class="rounded-2xl bg-white/12 p-4 backdrop-blur">
                        <div class="flex items-center justify-between text-sm font-semibold text-emerald-50/80">
                            <span>Saldo bulan ini</span>
                            <span>+18%</span>
                        </div>
                        <p class="mt-2 text-3xl font-extrabold">Rp 24.800.000</p>
                        <div class="mt-4 h-2 rounded-full bg-white/15">
                            <div class="h-2 w-3/4 rounded-full bg-lime-300"></div>
                        </div>
                    </div>
                    <div class="grid grid-cols-3 gap-3">
                        <div class="rounded-2xl bg-white/12 p-4">
                            <p class="text-xs font-semibold text-emerald-50/70">Income</p>
                            <p class="mt-1 font-extrabold">Rp 8,4 jt</p>
                        </div>
                        <div class="rounded-2xl bg-white/12 p-4">
                            <p class="text-xs font-semibold text-emerald-50/70">Expense</p>
                            <p class="mt-1 font-extrabold">Rp 3,1 jt</p>
                        </div>
                        <div class="rounded-2xl bg-white/12 p-4">
                            <p class="text-xs font-semibold text-emerald-50/70">Margin</p>
                            <p class="mt-1 font-extrabold">63%</p>
                        </div>
                    </div>
                </div>
            </section>

            <section class="flex min-h-screen items-center justify-center px-5 py-10 sm:px-8">
                <div class="w-full max-w-md">
                    <div class="mb-8 flex items-center justify-center gap-3 lg:hidden">
                        <x-application-logo class="h-11 w-11 text-emerald-500" />
                        <span class="text-xl font-extrabold tracking-tight text-slate-900">AkuntingPro</span>
                    </div>

                    <div class="rounded-[2rem] border border-emerald-100 bg-white p-6 shadow-xl shadow-emerald-100/70 sm:p-8">
                        {{ $slot }}
                    </div>
                </div>
            </section>
        </div>
    </main>
</body>

</html>
