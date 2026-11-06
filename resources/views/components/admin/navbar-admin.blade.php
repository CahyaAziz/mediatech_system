<header class="sticky top-0 z-20 flex h-[68px] shrink-0 items-center justify-between gap-3 border-b border-[#e8eaf0] bg-white px-[15px] sm:px-[22px] xl:px-[30px]">
    <div class="flex min-w-0 items-center gap-[13px]">
        {{-- Mobile sidebar control. Uses a checkbox in the layout; no JavaScript needed. --}}
        <label
            for="sidebar-toggle"
            class="grid h-9 w-9 shrink-0 cursor-pointer place-items-center rounded-lg border border-[#e5e5e5] bg-white text-[#525252] transition hover:bg-[#f5f6fb] hover:text-[#0d21a1] min-[721px]:hidden"
            aria-label="Buka navigasi"
            title="Buka navigasi"
        >
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round"><path d="M4 6h16M4 12h16M4 18h16"/></svg>
        </label>

        <div class="min-w-0">
            <div class="truncate text-[15px] font-bold leading-tight tracking-[-0.2px] text-[#262626] sm:text-[16px]">
                @yield('page_title', 'Dashboard')
            </div>
            <div class="mt-[3px] hidden items-center gap-[7px] text-[11px] font-medium text-[#a1a1a1] sm:flex">
                <span>Meditech System</span>
                <span class="text-[#d4d4d4]">/</span>
                <span class="truncate text-[#0d21a1]">@yield('page_breadcrumb', 'Workspace')</span>
            </div>
        </div>
    </div>

    <div class="flex shrink-0 items-center gap-2 sm:gap-3">
        {{-- Search is a visual entry point; connect its action to the global-search route when that module is ready. --}}
        <form action="{{ url('/admin/search') }}" method="GET" class="relative hidden w-[190px] lg:block xl:w-[240px]">
            <svg class="absolute left-[11px] top-1/2 h-[16px] w-[16px] -translate-y-1/2 text-[#a1a1a1]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"><circle cx="10.8" cy="10.8" r="6.8"/><path d="m16 16 4.5 4.5"/></svg>
            <input
                type="search"
                name="q"
                value="{{ request('q') }}"
                placeholder="Cari data, transaksi…"
                aria-label="Cari data dan transaksi"
                class="h-[36px] w-full rounded-lg border border-[#e5e5e5] bg-[#fafafa] pl-[34px] pr-3 text-[11px] text-[#404040] outline-none transition placeholder:text-[#a1a1a1] focus:border-[#9099d4] focus:bg-white focus:ring-2 focus:ring-[#e7e9f6]"
            >
        </form>

        <a
            href="{{ url('/admin/notifications') }}"
            class="relative grid h-9 w-9 place-items-center rounded-lg border border-[#e5e5e5] bg-white text-[#525252] transition hover:border-[#b4bae2] hover:bg-[#f5f6fb] hover:text-[#0d21a1]"
            aria-label="Notifikasi"
            title="Notifikasi"
        >
            <svg class="h-[17px] w-[17px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M18 9a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9M10 21h4"/></svg>
            @if (($unreadNotificationsCount ?? 0) > 0)
                <span class="absolute right-[7px] top-[7px] h-[6px] w-[6px] rounded-full border border-white bg-[#eec643]"></span>
            @endif
        </a>

        <details class="group relative">
            <summary class="flex cursor-pointer list-none items-center gap-2 rounded-lg p-1 transition hover:bg-[#f5f6fb] [&::-webkit-details-marker]:hidden">
                <span class="grid h-[34px] w-[34px] place-items-center rounded-full bg-[#e7e9f6] text-[12px] font-extrabold text-[#0d21a1]">
                    {{ strtoupper(collect(explode(' ', trim(auth()->user()->name ?? 'User')))->filter()->take(2)->map(fn ($part) => mb_substr($part, 0, 1))->implode('')) }}
                </span>
                <span class="hidden max-w-[150px] text-left sm:block">
                    <span class="block truncate text-[11px] font-bold text-[#262626]">{{ auth()->user()->name ?? 'Pengguna' }}</span>
                    <span class="mt-[1px] block truncate text-[10px] text-[#737373]">{{ auth()->user()?->roles?->pluck('role_name')->join(', ') ?: 'Pengguna' }}</span>
                </span>
                <svg class="hidden h-3 w-3 text-[#737373] transition group-open:rotate-180 sm:block" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m7 10 5 5 5-5"/></svg>
            </summary>
            <div class="absolute right-0 top-[calc(100%+10px)] z-50 w-52 overflow-hidden rounded-xl border border-[#e8eaf0] bg-white py-1 shadow-[0_12px_30px_rgba(20,27,55,0.12)]">
                <div class="border-b border-[#f0f1f5] px-4 py-3">
                    <p class="truncate text-[12px] font-bold text-[#262626]">{{ auth()->user()->name ?? 'Pengguna' }}</p>
                    <p class="mt-1 truncate text-[10px] text-[#737373]">{{ auth()->user()->email ?? '' }}</p>
                </div>
                <a href="{{ url('/admin/profile') }}" class="block px-4 py-2.5 text-[12px] font-medium text-[#525252] hover:bg-[#f5f6fb] hover:text-[#0d21a1]">Profil saya</a>
                <form action="{{ route('logout') }}" method="POST" class="border-t border-[#f0f1f5]">
                    @csrf
                    <button type="submit" class="w-full px-4 py-2.5 text-left text-[12px] font-semibold text-[#b54740] hover:bg-[#fff0ef]">Keluar</button>
                </form>
            </div>
        </details>
    </div>
</header>
