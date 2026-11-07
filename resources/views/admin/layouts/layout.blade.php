<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') | Meditech</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>

<body class="min-h-screen overflow-x-hidden bg-[#f7f8fb] font-sans text-neutral-800 antialiased">
    <div class="min-h-screen">
        <svg class="absolute h-0 w-0 overflow-hidden" aria-hidden="true" focusable="false"
            xmlns="http://www.w3.org/2000/svg">
            <defs>
                <symbol id="i-dashboard" viewBox="0 0 24 24">
                    <rect fill="currentColor" fill-opacity=".14" height="10" rx="2.5" width="8" x="3"
                        y="3"></rect>
                    <rect height="6" rx="2.5" width="8" x="13" y="3"></rect>
                    <rect fill="currentColor" fill-opacity=".14" height="10" rx="2.5" width="8" x="13"
                        y="11"></rect>
                    <rect height="6" rx="2.5" width="8" x="3" y="15"></rect>
                </symbol>
                <symbol id="i-quotation" viewBox="0 0 24 24">
                    <path d="M6 3h8l5 5v11a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2z" fill="currentColor"
                        fill-opacity=".14"></path>
                    <path d="M14 3v5h5M8 13h8M8 17h5"></path>
                </symbol>
                <symbol id="i-order" viewBox="0 0 24 24">
                    <rect fill="currentColor" fill-opacity=".14" height="16.5" rx="3" width="14" x="5"
                        y="4.5"></rect>
                    <rect fill="#fff" height="4" rx="1.5" width="7" x="8.5" y="2.5"></rect>
                    <path d="m9 14 2 2 4-4"></path>
                </symbol>
                <symbol id="i-invoice" viewBox="0 0 24 24">
                    <path d="M6 3h12v18l-3-2-3 2-3-2-3 2z" fill="currentColor" fill-opacity=".14"></path>
                    <path d="M9 8h6M9 12h6"></path>
                </symbol>
                <symbol id="i-payment" viewBox="0 0 24 24">
                    <rect fill="currentColor" fill-opacity=".14" height="14" rx="3" width="18" x="3"
                        y="5"></rect>
                    <path d="M3 10h18M7 15h3"></path>
                </symbol>
                <symbol id="i-swap" viewBox="0 0 24 24">
                    <path d="M7 8h12l-3-3M17 16H5l3 3"></path>
                </symbol>
                <symbol id="i-inventory" viewBox="0 0 24 24">
                    <path d="M3.5 7.5 12 3l8.5 4.5v9L12 21l-8.5-4.5z" fill="currentColor" fill-opacity=".14"></path>
                    <path d="m3.5 7.5 8.5 4.5 8.5-4.5M12 12v9"></path>
                </symbol>
                <symbol id="i-database" viewBox="0 0 24 24">
                    <ellipse cx="12" cy="6" fill="currentColor" fill-opacity=".14" rx="8"
                        ry="3"></ellipse>
                    <path d="M4 6v6c0 1.7 3.6 3 8 3s8-1.3 8-3V6M4 12v6c0 1.7 3.6 3 8 3s8-1.3 8-3v-6"></path>
                </symbol>
                <symbol id="i-users" viewBox="0 0 24 24">
                    <circle cx="9" cy="8" fill="currentColor" fill-opacity=".14" r="3.5"></circle>
                    <path
                        d="M2.5 20c0-3.6 2.9-6 6.5-6s6.5 2.4 6.5 6M16 4.6a3.5 3.5 0 0 1 0 6.8M18 14.4c2 .7 3.5 2.6 3.5 5.6">
                    </path>
                </symbol>
                <symbol id="i-cms" viewBox="0 0 24 24">
                    <rect fill="currentColor" fill-opacity=".14" height="16" rx="3" width="18" x="3"
                        y="4"></rect>
                    <path d="M3 9h18"></path>
                    <circle cx="8" cy="14.5" r="1.5"></circle>
                    <path d="m11 18 3-3.5 3 3.5"></path>
                </symbol>
                <symbol id="i-audit" viewBox="0 0 24 24">
                    <path d="M12 3 4.5 6v5.5c0 4.4 3 8 7.5 9.5 4.5-1.5 7.5-5.1 7.5-9.5V6z" fill="currentColor"
                        fill-opacity=".14"></path>
                    <path d="m9 12 2.2 2.2L15.5 10"></path>
                </symbol>
                <symbol id="i-trend" viewBox="0 0 24 24">
                    <path d="M3 17 9 11l4 4 8-9"></path>
                    <path d="M15 6h6v6"></path>
                </symbol>
                <symbol id="i-chev" viewBox="0 0 24 24">
                    <path d="m6 9 6 6 6-6"></path>
                </symbol>
                <symbol id="i-plus" viewBox="0 0 24 24">
                    <path d="M12 5v14M5 12h14"></path>
                </symbol>
                <symbol id="i-search" viewBox="0 0 24 24">
                    <circle cx="11" cy="11" r="6.5"></circle>
                    <path d="m16 16 4.5 4.5"></path>
                </symbol>
                <symbol id="i-download" viewBox="0 0 24 24">
                    <path d="M12 4v11m0 0 4-4m-4 4-4-4M5 20h14"></path>
                </symbol>
            </defs>
        </svg>
        {{-- Input, sidebar, konten, dan overlay berada dalam satu level agar peer selector Tailwind bekerja. --}}
        <input id="sidebar-toggle" type="checkbox" class="peer/sidebar-toggle sr-only" aria-label="Toggle navigasi">
        <x-admin.sidebar-admin />
        <div class="flex min-h-screen min-w-0 flex-col min-[768px]:ml-[248px]">
            <x-admin.navbar-admin />
            <main
                class="mx-auto w-full max-w-[1600px] min-w-0 flex-1 px-[14px] pb-10 pt-[22px] sm:px-[22px] sm:pt-[27px] xl:px-[30px]">
                @if (session('success'))
                    <div role="status"
                        class="mb-4 flex items-start gap-3 rounded-lg border border-[#bfe5cf] bg-[#eaf7f0] px-4 py-3 text-[12px] font-medium text-[#16805d]">
                        <span>{{ session('success') }}</span></div>
                @endif
                @if (session('error') || session('error_access'))
                    <div role="alert"
                        class="mb-4 flex items-start gap-3 rounded-lg border border-[#f2c8c5] bg-[#fff0ef] px-4 py-3 text-[12px] font-medium text-[#b54740]">
                        <span>{{ session('error') ?? session('error_access') }}</span></div>
                @endif
                @if ($errors->any())
                    <div role="alert"
                        class="mb-4 rounded-lg border border-[#f2c8c5] bg-[#fff0ef] px-4 py-3 text-[11px] leading-relaxed text-[#b54740]">
                        <p class="font-bold">Periksa kembali data yang dimasukkan.</p>
                        <ul class="mt-1 list-inside list-disc">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @yield('content')
            </main>
        </div>
        <label for="sidebar-toggle"
            class="fixed inset-0 z-30 hidden cursor-pointer bg-[#111827]/40 peer-checked/sidebar-toggle:block min-[768px]:hidden"
            aria-label="Tutup navigasi"></label>
    </div>
    @stack('scripts')
</body>

</html>
