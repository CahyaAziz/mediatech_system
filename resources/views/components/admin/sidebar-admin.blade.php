<aside
    id="sidebar"
    class="fixed inset-y-0 left-0 z-40 flex w-[248px] -translate-x-full flex-col border-r border-[#e8eaf0] bg-white transition-transform duration-200 ease-out peer-checked/sidebar-toggle:translate-x-0 min-[721px]:translate-x-0"
    aria-label="Navigasi utama"
>
    {{-- Brand --}}
    <a href="{{ url('/dashboard') }}" class="flex shrink-0 items-center gap-[11px] border-b border-[#f0f1f5] px-5 py-[22px] no-underline">
        <img src="{{ asset('images/meditech-logo.jpg') }}" alt="Logo Meditech Cipta Inovasi" class="h-[40px] w-[62px] shrink-0 rounded-md object-contain">
        <span class="min-w-0">
            <span class="block text-[16px] font-extrabold tracking-[-0.5px] text-[#171717]">
                <span class="text-[#0d21a1]">Medi</span><span class="text-[#eec643]">Tech</span>
            </span>
            <span class="mt-[2px] block text-[10px] font-bold uppercase tracking-[1.25px] text-[#737373]">Business System</span>
        </span>
    </a>

    {{-- Navigation --}}
    <nav class="flex-1 space-y-1 overflow-y-auto px-3 py-3 [&::-webkit-scrollbar]:w-1 [&::-webkit-scrollbar-thumb]:rounded-full [&::-webkit-scrollbar-thumb]:bg-[#d4d4d4]" aria-label="Menu aplikasi">
        @php
            $navigationGroups = [
                [
                    'label' => 'Workspace',
                    'items' => [
                        ['label' => 'Dashboard', 'icon' => 'dashboard', 'permission' => 'dashboard.read', 'route' => 'dashboard', 'active' => ['dashboard']],
                    ],
                ],
                [
                    'label' => 'Sales & Finance',
                    'items' => [
                        ['label' => 'Quotation', 'icon' => 'quotation', 'permission' => 'quotations.read', 'route' => 'quotations.index', 'active' => ['quotations.*'], 'count' => $pendingQuotationsCount ?? null],
                        ['label' => 'Sales Order', 'icon' => 'order', 'permission' => 'sales_orders.read', 'route' => 'sales-orders.index', 'active' => ['sales-orders.*']],
                        ['label' => 'Invoice', 'icon' => 'invoice', 'permission' => 'invoices.read', 'route' => 'invoices.index', 'active' => ['invoices.*']],
                        ['label' => 'Payment', 'icon' => 'payment', 'permission' => 'payments.read', 'route' => 'payments.index', 'active' => ['payments.*']],
                        ['label' => 'Cash Flow', 'icon' => 'cash', 'permission' => 'cash_flow.read', 'route' => 'cash-flow.index', 'active' => ['cash-flow.*']],
                        ['label' => 'Petty Cash', 'icon' => 'petty', 'permission' => 'petty_cash.read', 'route' => 'petty-cash.index', 'active' => ['petty-cash.*']],
                        ['label' => 'Profit', 'icon' => 'profit', 'permission' => 'profit.read', 'route' => 'profit.index', 'active' => ['profit.*']],
                        ['label' => 'Aturan Komisi', 'icon' => 'commission', 'permission' => 'commission_rules.read', 'route' => 'commissions.index', 'active' => ['commissions.*']],
                    ],
                ],
                [
                    'label' => 'Operations',
                    'items' => [
                        ['label' => 'Inventory', 'icon' => 'inventory', 'permission' => 'inventory.read', 'route' => 'inventory.index', 'active' => ['inventory.*']],
                        ['label' => 'Master Data', 'icon' => 'database', 'permission' => 'master_data.read', 'route' => 'master-data.index', 'active' => ['master-data.*']],
                    ],
                ],
                [
                    'label' => 'Administration',
                    'items' => [
                        ['label' => 'User Management', 'icon' => 'users', 'permission' => 'users.read', 'route' => 'admin.users.index', 'active' => ['admin.users.*']],
                        ['label' => 'Manajemen Role', 'icon' => 'roles', 'permission' => 'role_management.read', 'route' => 'admin.roles.index', 'active' => ['admin.roles.*']],
                        ['label' => 'CMS Content', 'icon' => 'cms', 'permission' => 'cms.read', 'route' => 'cms.pages.index', 'active' => ['cms.*']],
                        ['label' => 'Audit Log', 'icon' => 'audit', 'permission' => 'audit_logs.read', 'route' => 'admin.audit-logs.index', 'active' => ['admin.audit-logs.*']],
                    ],
                ],
            ];
        @endphp

        @foreach ($navigationGroups as $group)
            @php
                $visibleItems = collect($group['items'])->filter(fn ($item) => auth()->user()?->can($item['permission']));
            @endphp

            @if ($visibleItems->isNotEmpty())
                <div class="px-[11px] pb-2 pt-[14px] text-[10px] font-extrabold uppercase tracking-[1.2px] text-[#a1a1a1]">
                    {{ $group['label'] }}
                </div>

                @foreach ($visibleItems as $item)
                    @php
                        $isActive = collect($item['active'])->contains(fn ($pattern) => request()->routeIs($pattern));
                        $href = \Illuminate\Support\Facades\Route::has($item['route']) ? route($item['route']) : url('/');
                    @endphp

                    <a
                        href="{{ $href }}"
                        @if ($isActive) aria-current="page" @endif
                        class="mb-[2px] flex items-center gap-3 rounded-lg px-[11px] py-[10px] text-[13px] transition-colors duration-150 {{ $isActive ? 'bg-[#e7e9f6] font-bold text-[#0d21a1]' : 'font-medium text-[#525252] hover:bg-[#f5f6fb] hover:text-[#0d21a1]' }}"
                    >
                        <span class="grid h-[18px] w-[18px] shrink-0 place-items-center">
                            @switch($item['icon'])
                                @case('dashboard')
                                    <svg class="h-[18px] w-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="8" height="8" rx="1.5"/><rect x="13" y="3" width="8" height="5" rx="1.5"/><rect x="13" y="10" width="8" height="11" rx="1.5"/><rect x="3" y="13" width="8" height="8" rx="1.5"/></svg>
                                    @break
                                @case('quotation')
                                    <svg class="h-[18px] w-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M7 3.75h7l4 4v12.5H7a2 2 0 0 1-2-2V5.75a2 2 0 0 1 2-2Z"/><path d="M14 3.75v4h4M8.5 12h7M8.5 15.5h7"/></svg>
                                    @break
                                @case('order')
                                    <svg class="h-[18px] w-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M7 3.5h10l3 3v14H4v-14l3-3Z"/><path d="M8 10h8M8 13.5h8M8 17h5"/></svg>
                                    @break
                                @case('invoice')
                                    <svg class="h-[18px] w-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M6 3.5h12v17l-3-1.8-3 1.8-3-1.8-3 1.8v-17Z"/><path d="M9 8h6M9 11.5h6M9 15h3"/></svg>
                                    @break
                                @case('payment')
                                @case('petty')
                                    <svg class="h-[18px] w-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 9h18M7 14h3"/></svg>
                                    @break
                                @case('cash')
                                    <svg class="h-[18px] w-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7h15a2 2 0 0 1 2 2v9H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h13"/><path d="M16 12h5M7 12h.01"/></svg>
                                    @break
                                @case('profit')
                                @case('commission')
                                    <svg class="h-[18px] w-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="m4 17 5-5 4 3 7-8"/><path d="M14 7h6v6"/></svg>
                                    @break
                                @case('inventory')
                                    <svg class="h-[18px] w-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><path d="m12 3 9 5v8l-9 5-9-5V8l9-5Z"/><path d="m3.5 8.2 8.5 4.7 8.5-4.7M12 13v8"/></svg>
                                    @break
                                @case('database')
                                    <svg class="h-[18px] w-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><ellipse cx="12" cy="5" rx="8" ry="3"/><path d="M4 5v14c0 1.7 3.6 3 8 3s8-1.3 8-3V5M4 12c0 1.7 3.6 3 8 3s8-1.3 8-3"/></svg>
                                    @break
                                @case('users')
                                @case('roles')
                                    <svg class="h-[18px] w-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="9" cy="8" r="3.2"/><path d="M3.5 20v-1.3A5.2 5.2 0 0 1 8.7 13.5h.6a5.2 5.2 0 0 1 5.2 5.2V20M16 4.8a3.2 3.2 0 0 1 0 6.2M17 14a4.5 4.5 0 0 1 3.5 4.4V20"/></svg>
                                    @break
                                @case('cms')
                                    <svg class="h-[18px] w-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="16" rx="2"/><path d="M3 9h18M8 4v5M7 14h4M7 17h8"/></svg>
                                    @break
                                @case('audit')
                                    <svg class="h-[18px] w-[18px]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2M7 3 4 6"/></svg>
                                    @break
                            @endswitch
                        </span>

                        <span class="min-w-0 flex-1 truncate">{{ $item['label'] }}</span>

                        @if (!empty($item['count']))
                            <span class="rounded-[5px] bg-[#eec643] px-[6px] py-[2px] text-[10px] font-extrabold text-[#262626]">{{ $item['count'] }}</span>
                        @endif
                    </a>
                @endforeach
            @endif
        @endforeach
    </nav>

    {{-- Current user --}}
    <div class="shrink-0 border-t border-[#f0f1f5] p-[14px]">
        <div class="flex items-center gap-[10px] rounded-lg p-2">
            <span class="grid h-[34px] w-[34px] shrink-0 place-items-center rounded-full bg-[#e7e9f6] text-[12px] font-extrabold text-[#0d21a1]">
                {{ strtoupper(collect(explode(' ', trim(auth()->user()->name ?? 'User')))->filter()->take(2)->map(fn ($part) => mb_substr($part, 0, 1))->implode('')) }}
            </span>
            <span class="min-w-0 flex-1">
                <span class="block truncate text-[12px] font-bold text-[#262626]">{{ auth()->user()->name ?? 'Pengguna' }}</span>
                <span class="mt-[2px] block truncate text-[11px] text-[#737373]">
                    {{ auth()->user()?->roles?->pluck('role_name')->join(', ') ?: 'Pengguna' }}
                </span>
            </span>
            <span class="text-[#a1a1a1]" aria-hidden="true">
                <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="m7 10 5 5 5-5"/></svg>
            </span>
        </div>
    </div>
</aside>
