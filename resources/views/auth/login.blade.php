<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#0d21a1">
    <title>Login | Meditech System</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap"
        rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-[#f7f8fb] font-['Inter'] text-[#262626] antialiased">
    <main
        class="relative grid min-h-screen place-items-center overflow-hidden px-[14px] py-6 sm:px-[22px] sm:py-[34px]">
        <div aria-hidden="true"
            class="pointer-events-none absolute -right-32 -top-48 h-[400px] w-[400px] rounded-full bg-[#0d21a1]/[0.055]">
        </div>
        <div aria-hidden="true"
            class="pointer-events-none absolute -bottom-44 -left-28 h-[300px] w-[300px] rounded-full bg-[#eec643]/[0.12]">
        </div>

        <section
            class="relative z-10 grid min-h-[610px] w-full max-w-[1020px] overflow-hidden rounded-[18px] border border-[#e8eaf0] bg-white shadow-[0_24px_70px_rgba(19,31,83,0.09)] md:grid-cols-[0.92fr_1.08fr]"
            aria-label="Login Meditech System">
            <aside
                class="relative hidden flex-col overflow-hidden bg-gradient-to-br from-[#0d21a1] via-[#091772] to-[#071259] px-10 py-[42px] text-white md:flex">
                <div aria-hidden="true"
                    class="pointer-events-none absolute -right-[180px] top-[210px] h-[360px] w-[360px] rounded-full border border-white/10">
                </div>
                <div aria-hidden="true"
                    class="pointer-events-none absolute -right-[125px] top-[265px] h-[250px] w-[250px] rounded-full border border-[#eec643]/25">
                </div>
                <div class="relative z-10 flex items-center gap-3">
                    <img src="{{ asset('images/meditech-logo.jpg') }}" alt="Logo Meditech Cipta Inovasi"
                        class="h-[42px] w-[60px] rounded-[7px] bg-white object-contain p-[2px]">
                    <div>
                        <div
                            class="font-heading text-[20px] leading-7 font-bold tracking-[-0.5px]">
                            <span class="text-secondary-500">Medi</span><span class="text-primary-500">Tech</span></div>
                        <div class="mt-1 text-[10px] font-bold uppercase tracking-[1.8px] text-white/65">Business System
                        </div>
                    </div>
                </div>

                <div class="relative z-10 mt-auto pt-[78px]">
                    <div
                        class="inline-flex items-center gap-2 text-[10px] font-extrabold uppercase tracking-[1.5px] text-[#f5d96e]">
                        <span class="h-[2px] w-[22px] rounded bg-[#eec643]"></span>Meditech System</div>
                    <h1
                        class="mt-[18px] max-w-[340px] font-['Plus_Jakarta_Sans'] text-[clamp(27px,3vw,36px)] font-bold leading-[1.25] tracking-[-1.1px]">
                        Kelola operasional bisnis dalam satu sistem.</h1>
                    <p class="mt-[14px] max-w-[335px] text-[13px] leading-[1.8] text-white/75">Akses proses penjualan,
                        keuangan, persediaan, dan pengelolaan data perusahaan melalui satu platform terintegrasi.</p>
                </div>

                <div class="relative z-10 mt-[30px] grid gap-[14px] text-[12px] font-medium text-white/90">
                    <div class="flex items-center gap-[11px]"><span
                            class="grid h-7 w-7 place-items-center rounded-lg border border-white/20 bg-white/[0.08] text-[#f1d169]">▤</span>Penjualan
                        dan quotation</div>
                    <div class="flex items-center gap-[11px]"><span
                            class="grid h-7 w-7 place-items-center rounded-lg border border-white/20 bg-white/[0.08] text-[#f1d169]">▣</span>Keuangan
                        dan pembayaran</div>
                    <div class="flex items-center gap-[11px]"><span
                            class="grid h-7 w-7 place-items-center rounded-lg border border-white/20 bg-white/[0.08] text-[#f1d169]">◇</span>Inventory
                        dan pelaporan</div>
                </div>
                <div class="relative z-10 mt-[38px] text-[10px] text-white/50">© {{ now()->year }} Meditech System ·
                    Business Operations</div>
            </aside>

            <section class="flex flex-col justify-center px-6 py-7 sm:px-10 md:px-[clamp(28px,5vw,66px)] md:py-12">
                <div class="mb-8 flex items-center gap-3 md:hidden">
                    <img src="{{ asset('images/meditech-logo.jpg') }}" alt="Logo Meditech"
                        class="h-[37px] w-[51px] rounded-md bg-white object-contain">
                    <div>
                        <div class="font-heading text-[20px] leading-7 font-bold tracking-[-0.5px]"><span
                                class="text-secondary-500">Medi</span><span class="text-primary-500">Tech</span></div>                        <div class="text-[9px] font-bold uppercase tracking-[1.6px] text-neutral-500">Business System
                        </div>
                    </div>
                </div>

                <div>
                    <div class="text-[10px] font-extrabold uppercase tracking-[1.45px] text-[#0d21a1]">Business
                        Operations</div>
                    <h2
                        class="mt-[9px] font-['Plus_Jakarta_Sans'] text-[27px] font-extrabold leading-tight tracking-[-0.8px] text-[#171717]">
                        Selamat datang kembali</h2>
                    <p class="mt-[7px] text-[12px] text-[#737373]">Masuk menggunakan akun Meditech yang telah terdaftar.
                    </p>
                </div>

                @if (session('status'))
                    <div role="status"
                        class="mt-5 rounded-lg border border-[#bfe5cf] bg-[#eaf7f0] px-3 py-3 text-[11px] leading-relaxed text-[#16805d]">
                        {{ session('status') }}</div>
                @endif
                @if ($errors->any())
                    <div role="alert"
                        class="mt-5 rounded-lg border border-[#f2c8c5] bg-[#fff0ef] px-3 py-3 text-[11px] leading-relaxed text-[#b54740]">
                        <p class="font-bold">Login belum berhasil.</p>
                        <p class="mt-1">{{ $errors->first() }}</p>
                    </div>
                @endif

                <form method="POST" action="{{ route('login.store') }}" class="mt-[29px] space-y-[18px]">
                    @csrf
                    <div>
                        <label for="email" class="mb-[7px] block text-[11px] font-bold text-[#404040]">Email</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}"
                            autocomplete="username" required autofocus placeholder="nama@meditech.co.id"
                            class="h-[45px] w-full rounded-lg border border-[#dedfe6] bg-white px-[13px] text-[12px] text-[#262626] outline-none transition placeholder:text-[#a1a1a1] focus:border-[#5d6ac0] focus:ring-[3px] focus:ring-[#e7e9f6]">
                        @error('email')
                            <p class="mt-1 text-[10px] text-[#b54740]">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <div class="mb-[7px] flex items-center justify-between gap-3">
                            <label for="password" class="text-[11px] font-bold text-[#404040]">Kata sandi</label>
                            <a href="{{ route('password.request') }}"
                                class="text-[11px] font-bold text-[#0d21a1] hover:text-[#091772]">Lupa kata sandi?</a>
                        </div>
                        <div class="relative">
                            <input id="password" name="password" type="password" autocomplete="current-password"
                                required placeholder="Masukkan kata sandi"
                                class="h-[45px] w-full rounded-lg border border-[#dedfe6] bg-white px-[13px] pr-12 text-[12px] text-[#262626] outline-none transition placeholder:text-[#a1a1a1] focus:border-[#5d6ac0] focus:ring-[3px] focus:ring-[#e7e9f6]">
                            <button type="button" id="toggle-password"
                                class="absolute right-2 top-1/2 grid h-8 w-8 -translate-y-1/2 place-items-center rounded-md text-[#737373] hover:bg-[#f5f5f5]"
                                aria-label="Tampilkan kata sandi">
                                <svg id="password-eye" class="h-[17px] w-[17px]" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="1.7" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7S2 12 2 12Z" />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-1 text-[10px] text-[#b54740]">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between gap-3 pb-1">
                        <label class="flex cursor-pointer items-center gap-2 text-[11px] font-medium text-[#525252]">
                            <input type="checkbox" name="remember" value="1" @checked(old('remember'))
                                class="h-[14px] w-[14px] accent-[#0d21a1]">
                            Ingat saya
                        </label>
                        <span class="text-[10px] text-[#a1a1a1]">Akses khusus pengguna terdaftar</span>
                    </div>

                    <button type="submit"
                        class="flex min-h-[45px] w-full items-center justify-center gap-2 rounded-lg border border-[#0d21a1] bg-[#0d21a1] px-4 text-[12px] font-bold text-white shadow-[0_5px_12px_rgba(13,33,161,0.14)] transition hover:-translate-y-px hover:bg-[#091772] focus:outline-none focus:ring-4 focus:ring-[#e7e9f6]">
                        Masuk ke sistem
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M5 12h14M13 6l6 6-6 6" />
                        </svg>
                    </button>
                </form>

                <div
                    class="mt-[29px] flex justify-between gap-3 border-t border-[#f0f1f5] pt-[18px] text-[10px] text-[#a1a1a1]">
                    <span>Meditech System</span><span class="font-bold text-[#737373]">Business Operations</span>
                </div>
                <div class="mt-5 flex items-center justify-center gap-2 text-[10px] text-[#8a8a8a]">
                    <svg class="h-[13px] w-[13px] text-[#16805d]" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="4" y="10" width="16" height="11" rx="2" />
                        <path d="M8 10V7a4 4 0 1 1 8 0v3" />
                    </svg>
                    Koneksi dan sesi login dilindungi oleh sistem.
                </div>
            </section>
        </section>
    </main>

    <script>
        // Interaksi kecil untuk tombol lihat/sembunyikan password; layout tetap dibangun dengan Blade + Tailwind.
        const togglePassword = document.getElementById('toggle-password');
        const passwordInput = document.getElementById('password');
        togglePassword?.addEventListener('click', () => {
            const show = passwordInput.type === 'password';
            passwordInput.type = show ? 'text' : 'password';
            togglePassword.setAttribute('aria-label', show ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi');
        });
    </script>
</body>

</html>
