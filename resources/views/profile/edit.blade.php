<x-app-layout>
    <x-slot name="header">
        <h2 class="font-extrabold text-xl text-slate-900 leading-tight">
            Profil Akun
        </h2>
    </x-slot>

    <div class="space-y-6">
        <div class="rounded-[2rem] border border-emerald-100 bg-white p-6 shadow-xl shadow-emerald-100/70">
            <p class="text-sm font-bold text-emerald-700">Pengaturan</p>
            <h1 class="mt-1 text-2xl font-extrabold tracking-tight text-slate-950">Kelola akun Anda</h1>
            <p class="mt-2 text-sm text-slate-500">Perbarui informasi profil, keamanan password, dan preferensi akun.</p>
        </div>

        <div class="space-y-6">
            <div class="rounded-[1.5rem] border border-emerald-100 bg-white p-6 shadow-sm shadow-emerald-100/60 sm:p-8">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="rounded-[1.5rem] border border-emerald-100 bg-white p-6 shadow-sm shadow-emerald-100/60 sm:p-8">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="rounded-[1.5rem] border border-rose-100 bg-white p-6 shadow-sm sm:p-8">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
