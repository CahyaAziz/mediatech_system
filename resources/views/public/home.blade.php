@extends("layouts.public")

@section("title", "PT Meditech Cipta Inovasi")
@section("meta_description", "Jasa konstruksi dan instalasi, serta perdagangan perlengkapan alat kesehatan, elektrikal, elektronik, mesin kantor, dan komputer.")

@section("content")
{{-- HERO --}}
<div class="pt-16">
    <div class="mx-auto max-w-[1180px] px-5 grid items-center gap-12 md:grid-cols-[1.05fr_.95fr]">
        <div>
            <h1 class="font-heading text-4xl font-semibold leading-[1.18] text-primary-500 md:text-5xl lg:text-6xl">{{ $fields["hero_title"] ?? "Mitra instalasi dan perlengkapan teknologi Anda" }}</h1>
            <p class="max-w-[58ch] text-base text-neutral-600 md:text-lg mb-8 mt-5">{{ $fields["hero_subtitle"] ?? "Jasa konstruksi dan instalasi, serta perdagangan perlengkapan alat kesehatan, elektrikal, elektronik, mesin kantor, dan komputer." }}</p>
            <div class="flex flex-wrap gap-3">
                <a href="#kontak" class="inline-block rounded-xl bg-secondary-500 px-6 py-3 font-heading text-[15px] font-semibold text-neutral-900 transition hover:bg-secondary-300">{{ $fields["cta_label"] ?? "Minta penawaran" }}</a>
                <a href="#produk" class="inline-block rounded-xl bg-white px-6 py-3 font-heading text-[15px] font-semibold text-primary-500 ring-2 ring-inset ring-primary-500 transition hover:bg-primary-50">{{ $fields["cta_secondary"] ?? "Lihat produk" }}</a>
            </div>
        </div>
        <img src="{{ $fields["hero_img"] ?? asset("images/meditech-logo.jpg") }}" alt="Gedung perkantoran modern" class="aspect-square w-full rounded-[32px] object-cover">
    </div>
    {{-- STATS CARD (overlap) --}}
    <div class="mx-auto max-w-[1180px] px-5">
        <div class="relative z-[2] -mb-14 mt-14 grid grid-cols-2 gap-y-5 rounded-3xl border border-neutral-200 bg-white px-2 py-7 shadow-[0_16px_40px_-16px_rgba(13,33,161,.25)] md:grid-cols-4">
            @php
                $stats = $items->get("stats", collect(["150+" => "Proyek terselesaikan", "60+" => "Klien yang percaya", "25+" => "Tim teknisi profesional", "15+" => "Brand mitra resmi"]));
            @endphp
            @forelse($stats as $key => $stat)
                @php
                    if (is_object($stat)) { $value = $stat->item_value; $label = $stat->title; } else { $value = $key; $label = $stat; }
                @endphp
                <div class="border-neutral-200 px-3 text-center md:border-r {{ $loop->last ? "md:border-r-0" : "" }}">
                    <b class="block font-heading text-3xl font-semibold leading-tight text-primary-500 md:text-4xl">{{ $value }}</b>
                    <span class="text-sm text-neutral-600">{{ $label }}</span>
                </div>
            @empty
                <div class="px-3 text-center col-span-2 md:col-span-4 text-neutral-500 text-sm">Belum ada data statistik.</div>
            @endforelse
        </div>
    </div>
</div>

{{-- TENTANG --}}
<section id="tentang" class="pb-[88px] pt-36">
    <div class="mx-auto max-w-[1180px] px-5 grid items-center gap-14 md:grid-cols-2">
        <div>
            <h2 class="font-heading text-3xl font-semibold leading-tight text-primary-500 md:text-4xl lg:text-5xl mb-4">{{ $fields["about_title"] ?? "Tentang perusahaan" }}</h2>
            <p class="max-w-[58ch] text-base text-neutral-600 md:text-lg">{{ $fields["about_description"] ?? "" }}</p>
            @if($fields["about_body2"] ?? false)
                <p class="mt-3.5 text-neutral-600">{{ $fields["about_body2"] }}</p>
            @endif
            @if($fields["about_quote"] ?? false)
                <div class="mt-7 rounded-r-2xl border-l-4 border-secondary-500 bg-secondary-50 px-5 py-4 font-heading font-medium leading-normal text-primary-500">{{ $fields["about_quote"] }}</div>
            @endif
        </div>
        <img src="{{ $fields["about_img"] ?? asset("images/meditech-logo.jpg") }}" alt="{{ $fields["about_title"] ?? "Tentang" }}" class="aspect-[4/3] w-full rounded-[32px] object-cover">
    </div>
</section>

{{-- VISI & MISI --}}
<section id="visi" class="bg-primary-500 py-[88px] text-white">
    <div class="mx-auto max-w-[1180px] px-5 grid items-start gap-9 md:grid-cols-[.9fr_1.1fr] md:gap-14">
        <div class="rounded-[28px] border-t-4 border-secondary-500 bg-white/10 p-7 md:p-9">
            <h2 class="font-heading text-3xl font-semibold text-white md:text-4xl lg:text-5xl">{{ $fields["section_visi"] ?? "Visi" }}</h2>
            <p class="mt-5 font-heading text-xl font-medium leading-snug md:text-3xl">"{{ $fields["visi"] ?? "" }}"</p>
        </div>
        <div>
            <h2 class="font-heading text-3xl font-semibold text-white md:text-4xl lg:text-5xl">{{ $fields["section_misi"] ?? "Misi" }}</h2>
            <ol class="mt-5">
                @forelse($items->get("misi", collect()) as $misiItem)
                <li class="grid grid-cols-[40px_1fr] items-start gap-4 border-t border-white/20 py-4 {{ $loop->last ? "border-b" : "" }}">
                    <b class="grid h-10 w-10 place-items-center rounded-full bg-secondary-500 font-heading font-semibold text-neutral-900">{{ $loop->iteration }}</b>
                    <p class="pt-1.5 text-primary-50">{{ $misiItem->item_value }}</p>
                </li>
                @empty
                <li class="border-t border-white/20 py-4 text-primary-50">Belum ada misi.</li>
                @endforelse
            </ol>
        </div>
    </div>
</section>

{{-- PRODUK & LAYANAN (all items as same card format) --}}
<section id="produk" class="py-[88px]">
    <div class="mx-auto max-w-[1180px] px-5">
        <div class="mb-11 max-w-2xl">
            <h2 class="font-heading text-3xl font-semibold leading-tight text-primary-500 md:text-4xl lg:text-5xl">{{ $fields["section_produk"] ?? "Produk & layanan" }}</h2>
            <p class="max-w-[58ch] text-base text-neutral-600 md:text-lg mt-3">{{ $fields["page_intro"] ?? "Instalasi jasa untuk fasilitas kesehatan dan perkantoran, ditambah perangkat keras dan layanan perawatan." }}</p>
        </div>
        <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
            @forelse($items->get("service", collect()) as $service)
            <article class="overflow-hidden rounded-[20px] border border-neutral-200 bg-white">
                <img src="{{ $service->media?->file_path ?? asset("images/meditech-logo.jpg") }}" alt="{{ $service->title }}" class="aspect-[16/10] w-full object-cover">
                <div class="p-5">
                    <span class="mb-2 inline-block rounded-full bg-secondary-500 px-2.5 py-0.5 font-heading text-xs font-semibold text-neutral-900">{{ $service->meta ?? "Instalasi jasa" }}</span>
                    <h3 class="font-heading text-xl font-semibold leading-snug text-primary-500">{{ $service->title }}</h3>
                </div>
            </article>
            @empty
            <div class="col-span-full text-center text-neutral-500 text-sm py-8">Belum ada layanan.</div>
            @endforelse
        </div>
    </div>
</section>

{{-- PROYEK TERBARU --}}
<section id="proyek" class="py-[88px]">
    <div class="mx-auto max-w-[1180px] px-5">
        <div class="mb-11 max-w-2xl">
            <h2 class="font-heading text-3xl font-semibold leading-tight text-primary-500 md:text-4xl lg:text-5xl">{{ $fields["section_proyek"] ?? "Proyek terbaru" }}</h2>
            <p class="max-w-[58ch] text-base text-neutral-600 md:text-lg mt-3">{{ $fields["project_intro"] ?? "Beberapa pekerjaan yang telah kami selesaikan." }}</p>
        </div>
        <div class="grid gap-5 md:grid-cols-3">
            @forelse($items->get("project", collect()) as $project)
            <div class="overflow-hidden rounded-[20px] border border-neutral-200 bg-white p-6">
                <span class="mb-2 inline-block rounded-full bg-secondary-500 px-2.5 py-0.5 font-heading text-xs font-semibold text-neutral-900">{{ $project->meta ?? "" }}</span>
                <h3 class="font-heading text-xl font-semibold leading-snug text-primary-500">{{ $project->title }}</h3>
                <small class="mt-2.5 block text-[13px] text-neutral-600">{{ $project->subtitle ?? "" }}</small>
            </div>
            @empty
            <div class="col-span-full text-center text-neutral-500 text-sm py-8">Belum ada proyek.</div>
            @endforelse
        </div>
    </div>
</section>

{{-- DIPERCAYA BERBAGAI SEKTOR --}}
<section id="pelanggan" class="bg-neutral-50 py-[88px]">
    <div class="mx-auto max-w-[1180px] px-5">
        <div class="mb-11 max-w-2xl mx-auto text-center">
            <h2 class="font-heading text-3xl font-semibold leading-tight text-primary-500 md:text-4xl lg:text-5xl">Dipercaya berbagai sektor</h2>
            <p class="max-w-[58ch] text-base text-neutral-600 md:text-lg mt-3 mx-auto">{{ $fields["sector_intro"] ?? "Kami membangun kemitraan yang erat dengan pelanggan dalam mencapai tujuan mereka, terutama di bidang teknologi." }}</p>
        </div>
        <div class="flex flex-wrap justify-center gap-5">
            @php
                $sectors = [
                    ["name" => "Perbankan", "icon" => "M10 17l12-6 12 6M12 17v0h20M14 20v9M20 20v9M24 20v9M30 20v9M11 32h22"],
                    ["name" => "Perusahaan oli & gas", "icon" => "M22 10c5 6 8 10 8 14a8 8 0 0 1-16 0c0-4 3-8 8-14z M22 32c-2 0-3-1.500-3-3"],
                    ["name" => "Manufaktur", "icon" => "M10 32V20l8 5v-5l8 5v-5l8 5v7z M14 32v-4M22 32v-4M30 32v-4"],
                    ["name" => "Perusahaan distribusi", "icon" => "M9 14h16v14H9z M25 19h6l4 5v4H25z"],
                    ["name" => "Rumah sakit", "icon" => "M12 12h20v20H12z M22 17v10M17 22h10"],
                    ["name" => "Retail", "icon" => "M12 17h20l-1.500 15h-17z M17 17v-2a5 5 0 0 1 10 0v2"],
                    ["name" => "Pendidikan", "icon" => "M22 12l13 6-13 6-13-6z M15 21v6c0 2 3.500 4 7 4s7-2 7-4v-6 M35 18v8"],
                ];
            @endphp
            @foreach($sectors as $sector)
            <div class="min-w-0 basis-[calc(50%-10px)] rounded-[20px] border border-neutral-200 bg-white px-3 py-5 text-center transition hover:border-secondary-500 hover:shadow-[0_12px_28px_-12px_rgba(13,33,161,.25)] sm:min-w-[200px] sm:basis-[calc(25%-15px)] sm:px-5 sm:py-7">
                <svg class="mx-auto mb-4 block h-[60px] w-[60px]" viewBox="0 0 44 44" fill="none">
                    <rect width="44" height="44" rx="14" fill="#e7e9f6"/>
                    <g stroke="#0D21A1" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="{{ $sector['icon'] }}"/>
                    </g>
                </svg>
                <h4 class="font-heading text-base font-semibold leading-snug text-primary-500">{{ $sector['name'] }}</h4>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- PARTNER --}}
<section id="partner" class="py-[88px]">
    <div class="mx-auto max-w-[1180px] px-5">
        <div class="mb-11 max-w-2xl mx-auto text-center">
            <h2 class="font-heading text-3xl font-semibold leading-tight text-primary-500 md:text-4xl lg:text-5xl">{{ $fields["section_partner"] ?? "Brand yang bekerja sama dengan kami" }}</h2>
            <p class="max-w-[58ch] text-base text-neutral-600 md:text-lg mt-3 mx-auto">{{ $fields["partner_intro"] ?? "Kami memasok perangkat dari brand ternama dan mitra resmi." }}</p>
        </div>
        <ul class="grid grid-cols-2 gap-4 sm:grid-cols-4">
            @forelse($partners as $partner)
            <li class="grid h-24 place-items-center rounded-2xl border border-neutral-200 bg-white p-4 transition hover:border-secondary-500">
                @if($partner->media)
                    <img src="{{ $partner->media->file_path }}" alt="{{ $partner->partner_name }}" class="max-h-12 w-auto">
                @else
                    <span class="font-heading text-lg font-semibold text-primary-500">{{ $partner->partner_name }}</span>
                @endif
            </li>
            @empty
            <li class="col-span-full text-center text-neutral-500 text-sm py-8">Belum ada partner.</li>
            @endforelse
        </ul>
    </div>
</section>

{{-- TESTIMONI --}}
<section id="testimoni" class="bg-neutral-50 py-[88px]">
    <div class="mx-auto max-w-[1180px] px-5">
        <div class="mb-11 max-w-2xl mx-auto text-center">
            <h2 class="font-heading text-3xl font-semibold leading-tight text-primary-500 md:text-4xl lg:text-5xl">{{ $fields["section_testimoni"] ?? "Kata pelanggan kami" }}</h2>
        </div>
        <div class="grid gap-5 md:grid-cols-3">
            @forelse($items->get("testimoni", collect()) as $testi)
            <div class="overflow-hidden rounded-[20px] border border-neutral-200 bg-white p-[26px]">
                <svg viewBox="0 0 80 14" width="80" height="14" fill="#EEC643" aria-label="5 bintang"><path transform="translate(0,0)" d="M7 0l2 5 5 .5-4 3.5 1.2 5L7 11 2.8 14 4 9 0 5.5 5 5z"/><path transform="translate(16,0)" d="M7 0l2 5 5 .5-4 3.5 1.2 5L7 11 2.8 14 4 9 0 5.5 5 5z"/><path transform="translate(32,0)" d="M7 0l2 5 5 .5-4 3.5 1.2 5L7 11 2.8 14 4 9 0 5.5 5 5z"/><path transform="translate(48,0)" d="M7 0l2 5 5 .5-4 3.5 1.2 5L7 11 2.8 14 4 9 0 5.5 5 5z"/><path transform="translate(64,0)" d="M7 0l2 5 5 .5-4 3.5 1.2 5L7 11 2.8 14 4 9 0 5.5 5 5z"/></svg>
                <p class="mb-4 mt-3 text-[15px] text-neutral-600">"{{ $testi->description }}"</p>
                <b class="font-heading text-sm font-semibold text-primary-500">{{ $testi->title }}</b>
                <small class="block text-[13px] text-neutral-600">{{ $testi->subtitle }}</small>
            </div>
            @empty
            <div class="col-span-full text-center text-neutral-500 text-sm py-8">Belum ada testimoni.</div>
            @endforelse
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="bg-primary-500 py-[88px] text-center text-white">
    <div class="mx-auto max-w-[1180px] px-5">
        <h2 class="mb-3 font-heading text-3xl font-semibold text-white md:text-4xl lg:text-5xl">{{ $fields["cta_title"] ?? "Butuh instalasi atau perangkat baru?" }}</h2>
        <p class="mx-auto mb-7 max-w-[58ch] text-base text-primary-100 md:text-lg">{{ $fields["cta_sub"] ?? "Ceritakan kebutuhan Anda, tim kami siap menyiapkan penawaran." }}</p>
        <a href="#kontak" class="inline-block rounded-xl bg-secondary-500 px-6 py-3 font-heading text-[15px] font-semibold text-neutral-900 transition hover:bg-secondary-300">{{ $fields["cta_button"] ?? "Hubungi kami" }}</a>
    </div>
</section>

{{-- KONTAK --}}
<section id="kontak" class="py-[88px]">
    <div class="mx-auto max-w-[1180px] px-5 grid items-start gap-14 md:grid-cols-2">
        <div>
            <h2 class="font-heading text-3xl font-semibold leading-tight text-primary-500 md:text-4xl lg:text-5xl">{{ $fields["section_kontak"] ?? "Hubungi kami" }}</h2>
            <p class="max-w-[58ch] text-base text-neutral-600 md:text-lg mt-3">{{ $fields["contact_intro"] ?? "Kirim pesan atau datang langsung ke kantor kami." }}</p>
            <div class="mt-7 grid gap-4 text-neutral-600">
                <div class="border-l-4 border-secondary-500 pl-3.5"><b class="block font-heading text-sm font-semibold text-primary-500">{{ $fields["contact_label_address"] ?? "Alamat" }}</b>{{ $fields["contact_address"] ?? "-" }}</div>
                <div class="border-l-4 border-secondary-500 pl-3.5"><b class="block font-heading text-sm font-semibold text-primary-500">{{ $fields["contact_label_phone"] ?? "Telepon" }}</b><a href="tel:{{ $fields["contact_phone"] ?? "" }}" class="hover:text-primary-500">{{ $fields["contact_phone"] ?? "-" }}</a></div>
                <div class="border-l-4 border-secondary-500 pl-3.5"><b class="block font-heading text-sm font-semibold text-primary-500">{{ $fields["contact_label_email"] ?? "Email" }}</b><a href="mailto:{{ $fields["contact_email"] ?? "" }}" class="hover:text-primary-500">{{ $fields["contact_email"] ?? "-" }}</a></div>
                <div class="border-l-4 border-secondary-500 pl-3.5"><b class="block font-heading text-sm font-semibold text-primary-500">{{ $fields["contact_label_hours"] ?? "Jam operasional" }}</b>{{ $fields["contact_hours"] ?? "-" }}</div>
            </div>
        </div>
        <form id="contact-form" class="grid gap-3.5 rounded-3xl border border-neutral-200 bg-white p-7 shadow-[0_16px_40px_-16px_rgba(13,33,161,.2)]" action="#" method="POST">
            @csrf
            <label class="grid gap-1.5 text-sm font-medium">{{ $fields["form_name_label"] ?? "Nama" }}<input name="name" required autocomplete="name" class="rounded-xl border border-neutral-200 bg-neutral-50 px-3.5 py-3 text-base text-neutral-900 focus:outline-none focus:ring-2 focus:ring-primary-50"></label>
            <label class="grid gap-1.5 text-sm font-medium">{{ $fields["form_email_label"] ?? "Email" }}<input type="email" name="email" required autocomplete="email" class="rounded-xl border border-neutral-200 bg-neutral-50 px-3.5 py-3 text-base text-neutral-900 focus:outline-none focus:ring-2 focus:ring-primary-50"></label>
            <label class="grid gap-1.5 text-sm font-medium">{{ $fields["form_message_label"] ?? "Kebutuhan Anda" }}<textarea name="message" rows="4" required class="rounded-xl border border-neutral-200 bg-neutral-50 px-3.5 py-3 text-base text-neutral-900 focus:outline-none focus:ring-2 focus:ring-primary-50"></textarea></label>
            <button type="submit" class="rounded-xl bg-primary-500 px-6 py-3 font-heading text-[15px] font-semibold text-white transition hover:bg-primary-700">{{ $fields["form_submit"] ?? "Kirim pesan" }}</button>
            <div id="contact-ok" role="status" class="min-h-5 text-sm text-neutral-600"></div>
        </form>
    </div>
</section>
@endsection

@push("scripts")
    <script>
        document.querySelectorAll("a[href^=\"#\"]").forEach(a => {
            a.addEventListener("click", e => {
                const id = a.getAttribute("href");
                if (id.length > 1) {
                    const el = document.getElementById(id.slice(1));
                    if (el) { e.preventDefault(); el.scrollIntoView({ behavior: "smooth", block: "start" }); }
                }
            });
        });
    </script>
@endpush