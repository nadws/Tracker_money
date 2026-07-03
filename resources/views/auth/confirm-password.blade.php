<x-guest-layout>
    <div class="mb-8">
        <p class="text-sm font-bold text-emerald-600">Konfirmasi keamanan</p>
        <h1 class="mt-2 text-3xl font-extrabold tracking-tight text-slate-950">Masukkan password</h1>
        <p class="mt-2 text-sm leading-6 text-slate-500">Area ini berisi data sensitif. Konfirmasi password untuk melanjutkan.</p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-5">
        @csrf

        <div>
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <x-primary-button class="w-full">
            Konfirmasi
        </x-primary-button>
    </form>
</x-guest-layout>
