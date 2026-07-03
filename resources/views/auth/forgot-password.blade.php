<x-guest-layout>
    <div class="mb-8">
        <p class="text-sm font-bold text-emerald-600">Reset akses</p>
        <h1 class="mt-2 text-3xl font-extrabold tracking-tight text-slate-950">Lupa password?</h1>
        <p class="mt-2 text-sm leading-6 text-slate-500">Masukkan email akun Anda, kami akan kirim tautan untuk membuat password baru.</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <x-primary-button class="w-full">
            Kirim Link Reset
        </x-primary-button>

        <p class="text-center text-sm text-slate-500">
            Ingat password?
            <a href="{{ route('login') }}" class="font-bold text-emerald-700 hover:text-emerald-900">Masuk</a>
        </p>
    </form>
</x-guest-layout>
