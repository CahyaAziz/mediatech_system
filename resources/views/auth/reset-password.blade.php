<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Atur Ulang Kata Sandi | Meditech</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap"
        rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="grid min-h-screen place-items-center bg-[#f7f8fb] px-4 py-8 font-['Inter'] text-[#262626]">
    <main
        class="w-full max-w-[440px] rounded-2xl border border-[#e8eaf0] bg-white p-7 shadow-[0_20px_60px_rgba(19,31,83,0.08)] sm:p-9">
        <a href="{{ route('login') }}" class="text-[11px] font-bold text-[#0d21a1] hover:underline">← Kembali ke
            login</a>
        <h1 class="mt-7 font-['Plus_Jakarta_Sans'] text-2xl font-extrabold text-[#171717]">Atur ulang kata sandi</h1>
        <p class="mt-2 text-[12px] text-[#737373]">Gunakan kata sandi minimal 12 karakter.</p>
        @if ($errors->any())
            <div role="alert"
                class="mt-5 rounded-lg border border-[#f2c8c5] bg-[#fff0ef] p-3 text-[11px] text-[#b54740]">
                {{ $errors->first() }}</div>
        @endif
        <form method="POST" action="{{ route('password.update') }}" class="mt-6 space-y-4">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <div><label for="email" class="mb-2 block text-[11px] font-bold">Email</label><input id="email"
                    name="email" type="email" value="{{ old('email', $email) }}" required autocomplete="email"
                    class="h-11 w-full rounded-lg border border-[#dedfe6] px-3 text-[12px] outline-none focus:border-[#5d6ac0] focus:ring-2 focus:ring-[#e7e9f6]">
            </div>
            <div><label for="password" class="mb-2 block text-[11px] font-bold">Kata sandi baru</label><input
                    id="password" name="password" type="password" required autocomplete="new-password"
                    class="h-11 w-full rounded-lg border border-[#dedfe6] px-3 text-[12px] outline-none focus:border-[#5d6ac0] focus:ring-2 focus:ring-[#e7e9f6]">
            </div>
            <div><label for="password_confirmation" class="mb-2 block text-[11px] font-bold">Konfirmasi kata
                    sandi</label><input id="password_confirmation" name="password_confirmation" type="password" required
                    autocomplete="new-password"
                    class="h-11 w-full rounded-lg border border-[#dedfe6] px-3 text-[12px] outline-none focus:border-[#5d6ac0] focus:ring-2 focus:ring-[#e7e9f6]">
            </div>
            <button
                class="h-11 w-full rounded-lg bg-[#0d21a1] text-[12px] font-bold text-white hover:bg-[#091772]">Simpan
                kata sandi baru</button>
        </form>
    </main>
</body>

</html>
