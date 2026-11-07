<!doctype html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <title>@yield("title", $cmsFields["footer_company"] ?? "PT Meditech Cipta Inovasi")</title>
    <meta name="description" content="@yield("meta_description", "Jasa konstruksi dan instalasi, serta perdagangan perlengkapan alat kesehatan, elektrikal, elektronik, mesin kantor, dan komputer.")">
    @vite(["resources/css/app.css", "resources/js/app.js"])
    @stack("styles")
</head>
<body class="bg-white font-sans text-base leading-relaxed text-neutral-900 antialiased">
    <header class="sticky top-0 z-20 border-b border-neutral-200 bg-white">
        <div class="mx-auto max-w-[1180px] px-5 flex h-[68px] items-center justify-between gap-4">
            <a href="#top" class="flex items-center gap-2.5">
                <img src="{{ asset("images/meditech-logo.jpg") }}" alt="Logo Meditech" class="h-[42px]">
                <span class="font-heading text-[20px] leading-7 font-bold tracking-[-0.5px] text-neutral-900"><span class="text-secondary-500">Medi</span><span class="text-primary-500">Tech</span></span>
            </a>
            <nav aria-label="Menu utama" class="hidden gap-5 text-sm font-medium text-neutral-600 lg:flex">
                <a href="#tentang" class="hover:text-primary-500">{{ $cmsFields["nav_tentang"] ?? "Tentang" }}</a>
                <a href="#visi" class="hover:text-primary-500">{{ $cmsFields["nav_visi"] ?? "Visi & Misi" }}</a>
                <a href="#produk" class="hover:text-primary-500">{{ $cmsFields["nav_produk"] ?? "Produk" }}</a>
                <a href="#proyek" class="hover:text-primary-500">{{ $cmsFields["nav_proyek"] ?? "Proyek" }}</a>
                <a href="#partner" class="hover:text-primary-500">{{ $cmsFields["nav_partner"] ?? "Partner" }}</a>
                <a href="#testimoni" class="hover:text-primary-500">{{ $cmsFields["nav_testimoni"] ?? "Testimoni" }}</a>
            </nav>
            <a href="#kontak" class="inline-block rounded-xl bg-secondary-500 px-6 py-3 font-heading text-[15px] font-semibold text-neutral-900 transition hover:bg-secondary-300">{{ $cmsFields["header_cta"] ?? "Hubungi kami" }}</a>
        </div>
    </header>

    <main id="top">
        @yield("content")
    </main>

    <footer class="bg-[#011638] pb-6 pt-14 text-sm text-primary-100">
        <div class="mx-auto max-w-[1180px] px-5">
            <div class="grid gap-10 md:grid-cols-[1.4fr_1fr_1.4fr]">
                <div>
                    <h4 class="mb-3.5 font-heading font-semibold text-white">{{ $cmsFields["footer_company"] ?? "PT Meditech Cipta Inovasi" }}</h4>
                    <p>{{ $cmsFields["footer_tagline"] ?? "Motivation Creative Innovation With Technology" }}</p>
                </div>
                <div>
                    <h4 class="mb-3.5 font-heading font-semibold text-white">{{ $cmsFields["footer_menu"] ?? "Menu" }}</h4>
                    <a href="#tentang" class="block py-0.5 hover:text-white">{{ $cmsFields["nav_tentang"] ?? "Tentang" }}</a>
                    <a href="#visi" class="block py-0.5 hover:text-white">{{ $cmsFields["nav_visi"] ?? "Visi & Misi" }}</a>
                    <a href="#produk" class="block py-0.5 hover:text-white">{{ $cmsFields["nav_produk"] ?? "Produk" }}</a>
                    <a href="#proyek" class="block py-0.5 hover:text-white">{{ $cmsFields["nav_proyek"] ?? "Proyek" }}</a>
                    <a href="#partner" class="block py-0.5 hover:text-white">{{ $cmsFields["nav_partner"] ?? "Partner" }}</a>
                    <a href="#kontak" class="block py-0.5 hover:text-white">{{ $cmsFields["nav_kontak"] ?? "Kontak" }}</a>
                </div>
                <div>
                    <h4 class="mb-3.5 font-heading font-semibold text-white">{{ $cmsFields["footer_kontak"] ?? "Kontak" }}</h4>
                    <p>{{ $cmsFields["contact_address"] ?? "-" }}</p>
                    <p class="mt-2">{{ $cmsFields["contact_phone"] ?? "-" }} &middot; {{ $cmsFields["contact_email"] ?? "-" }}</p>
                </div>
            </div>
            <div class="mt-9 flex flex-wrap justify-between gap-3 border-t border-white/15 pt-5">
                <span>&copy; {{ date("Y") }} {{ $cmsFields["footer_company"] ?? "PT Meditech Cipta Inovasi" }}</span>
                <span>{{ $cmsFields["footer_copyright"] ?? "Hak cipta dilindungi" }}</span>
            </div>
        </div>
    </footer>

    @stack("scripts")
</body>
</html>