<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') | Meditech</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="min-h-screen overflow-x-hidden bg-[#f7f8fb] font-['Plus_Jakarta_Sans'] text-[#262626] antialiased">
    {{-- Checkbox controls the mobile drawer using CSS only. --}}
    <input id="sidebar-toggle" type="checkbox" class="peer/sidebar-toggle sr-only" aria-label="Toggle navigasi">

    <div class="min-h-screen">
        <x-admin.sidebar-admin />

        <div class="flex min-h-screen min-w-0 flex-col min-[721px]:ml-[248px]">
            <x-admin.navbar-admin />

            <main class="mx-auto w-full max-w-[1600px] flex-1 min-w-0 px-[14px] pb-10 pt-[22px] sm:px-[22px] sm:pt-[27px] xl:px-[30px]">
                @if (session('success'))
                    <div role="status" class="mb-4 flex items-start gap-3 rounded-lg border border-[#bfe5cf] bg-[#eaf7f0] px-4 py-3 text-[12px] font-medium text-[#16805d]">
                        <svg class="mt-[1px] h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m5 12 4 4L19 6"/></svg>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                @if (session('error') || session('error_access'))
                    <div role="alert" class="mb-4 flex items-start gap-3 rounded-lg border border-[#f2c8c5] bg-[#fff0ef] px-4 py-3 text-[12px] font-medium text-[#b54740]">
                        <svg class="mt-[1px] h-4 w-4 shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 8v5m0 3h.01M10.3 3.9 2.1 18a2 2 0 0 0 1.7 3h16.4a2 2 0 0 0 1.7-3L13.7 3.9a2 2 0 0 0-3.4 0Z"/></svg>
                        <span>{{ session('error') ?? session('error_access') }}</span>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    {{-- Clicking the backdrop unchecks the mobile sidebar toggle. --}}
    <label for="sidebar-toggle" class="fixed inset-0 z-30 hidden cursor-pointer bg-[#111827]/40 peer-checked/sidebar-toggle:block min-[721px]:hidden" aria-label="Tutup navigasi"></label>

    @stack('scripts')
</body>
</html>
