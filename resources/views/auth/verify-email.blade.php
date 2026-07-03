<x-guest-layout>
    <div class="mb-8">
        <p class="text-sm font-bold text-emerald-600">Verifikasi email</p>
        <h1 class="mt-2 text-3xl font-extrabold tracking-tight text-slate-950">Cek inbox Anda</h1>
        <p class="mt-2 text-sm leading-6 text-slate-500">Klik tautan verifikasi yang sudah kami kirim. Jika belum masuk, kirim ulang dari tombol di bawah.</p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-5 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-bold text-emerald-800">
            Link verifikasi baru sudah dikirim ke email Anda.
        </div>
    @endif

    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <x-primary-button>
                Kirim Ulang Email
            </x-primary-button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="text-sm font-bold text-slate-500 hover:text-slate-900">
                Keluar
            </button>
        </form>
    </div>
</x-guest-layout>
