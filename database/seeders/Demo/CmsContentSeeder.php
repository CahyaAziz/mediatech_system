<?php

namespace Database\Seeders\Demo;

use Database\Seeders\Support\SeederSupport;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CmsContentSeeder extends Seeder
{
    use SeederSupport;

    public function run(): void
    {
        if (!$this->isDemoEnvironment()) return;

        $adminId = $this->userId('anaqi@meditech.co.id');
        $pages = [
            ['slug' => 'about-us', 'title' => 'Tentang Kami'],
            ['slug' => 'services', 'title' => 'Layanan'],
            ['slug' => 'projects', 'title' => 'Portofolio Proyek'],
            ['slug' => 'contact', 'title' => 'Kontak'],
        ];
        foreach ($pages as $page) {
            DB::table('cms_pages')->updateOrInsert(
                ['slug' => $page['slug']],
                ['title' => $page['title'], 'status' => 'Published', 'updated_by' => $adminId, 'updated_at' => now(), 'deleted_at' => null, 'deleted_by' => null, 'deletion_reason' => null]
            );
        }

        $mediaRows = [
            ['file_name' => 'partner-technology.svg', 'file_path' => 'demo/cms/partner-technology.svg', 'media_type' => 'image/svg+xml', 'alt_text' => 'Logo partner teknologi', 'file_size_kb' => 2, 'width_px' => 600, 'height_px' => 400],
            ['file_name' => 'partner-network.svg', 'file_path' => 'demo/cms/partner-network.svg', 'media_type' => 'image/svg+xml', 'alt_text' => 'Logo partner jaringan', 'file_size_kb' => 2, 'width_px' => 600, 'height_px' => 400],
        ];
        foreach ($mediaRows as $media) {
            $existing = DB::table('cms_media')->where('file_path', $media['file_path'])->first();
            if ($existing) {
                DB::table('cms_media')->where('media_id', $existing->media_id)->update([...$media, 'uploaded_by' => $adminId, 'uploaded_at' => now()]);
            } else {
                DB::table('cms_media')->insert([...$media, 'uploaded_by' => $adminId, 'uploaded_at' => now(), 'deleted_at' => null, 'deleted_by' => null, 'deletion_reason' => null]);
            }
        }

        $pageFields = [
            'about-us' => [
                'hero_title' => 'Solusi Teknologi untuk Kebutuhan Bisnis',
                'hero_subtitle' => 'Mendukung kebutuhan perangkat, infrastruktur, dan layanan teknologi perusahaan.',
                'cta_label' => 'Minta penawaran',
                'cta_secondary' => 'Lihat produk',
                'about_title' => 'Tentang perusahaan',
                'about_description' => 'Meditech menyediakan solusi teknologi informasi, pengadaan perangkat, instalasi, serta dukungan pemeliharaan.',
                'about_body2' => 'Kami berkomitmen memberikan pelayanan terbaik, dengan ketepatan waktu dan kerja sama untuk melayani semua kebutuhan pelanggan.',
                'about_quote' => 'Great things are never done by one person. They\'re done by a team.',
                'visi' => 'Menjadi perusahaan yang memiliki integritas, mitra terpercaya, profesional, competitive dengan pelayanan purna jual terbaik.',
                'section_visi' => 'Visi',
                'section_misi' => 'Misi',
                'section_produk' => 'Produk & layanan',
                'page_intro' => 'Instalasi jasa untuk fasilitas kesehatan dan perkantoran, ditambah perangkat keras dan layanan perawatan.',
                'hardware_title' => 'Hardware',
                'maintenance_title' => 'Maintenance',
                'maintenance_desc' => 'Layanan perawatan berkala agar perangkat dan instalasi tetap berfungsi optimal.',
                'section_proyek' => 'Proyek terbaru',
                'project_intro' => 'Beberapa pekerjaan yang telah kami selesaikan.',
                'section_pelanggan' => 'Pelanggan kami',
                'customers_intro' => 'Kami membangun kemitraan yang erat dengan pelanggan dalam mencapai tujuan mereka.',
                'section_partner' => 'Brand yang bekerja sama dengan kami',
                'partner_intro' => 'Kami memasok perangkat dari brand ternama dan mitra resmi.',
                'section_testimoni' => 'Kata pelanggan kami',
                'cta_title' => 'Butuh instalasi atau perangkat baru?',
                'cta_sub' => 'Ceritakan kebutuhan Anda, tim kami siap menyiapkan penawaran.',
                'cta_button' => 'Hubungi kami',
                'section_kontak' => 'Hubungi kami',
                'contact_intro' => 'Kirim pesan atau datang langsung ke kantor kami.',
                'contact_label_address' => 'Alamat',
                'contact_label_phone' => 'Telepon',
                'contact_label_email' => 'Email',
                'contact_label_hours' => 'Jam operasional',
                'contact_email' => 'info@meditech.co.id',
                'contact_phone' => '021 - 8908 - 8898',
                'contact_address' => 'Jl. Neo Paloma II Blok S10 No 26 Cluster Garden Ville, Desa Ciakar, Kec. Panongan, Kab. Tangerang, Banten.',
                'contact_hours' => 'Senin–Jumat, 08.00–17.00 WIB',
                'form_name_label' => 'Nama',
                'form_email_label' => 'Email',
                'form_message_label' => 'Kebutuhan Anda',
                'form_submit' => 'Kirim pesan',
                'nav_tentang' => 'Tentang',
                'nav_visi' => 'Visi & Misi',
                'nav_produk' => 'Produk',
                'nav_proyek' => 'Proyek',
                'nav_pelanggan' => 'Pelanggan',
                'nav_partner' => 'Partner',
                'nav_testimoni' => 'Testimoni',
                'nav_kontak' => 'Kontak',
                'header_cta' => 'Hubungi kami',
                'footer_company' => 'PT Meditech Cipta Inovasi',
                'footer_tagline' => 'Motivation Creative Innovation With Technology',
                'footer_menu' => 'Menu',
                'footer_kontak' => 'Kontak',
                'footer_copyright' => 'Hak cipta dilindungi',
            ],
            'services' => [
                'page_intro' => 'Layanan pengadaan perangkat, instalasi, integrasi, dan pemeliharaan.',
            ],
            'projects' => [
                'page_intro' => 'Beberapa pekerjaan dan implementasi solusi teknologi.',
            ],
            'contact' => [
                'contact_email' => 'info@meditech.co.id',
                'contact_phone' => '021-0000-0000',
                'contact_address' => 'Alamat perusahaan perlu dilengkapi.',
            ],
        ];
        foreach ($pageFields as $slug => $fields) {
            $pageId = (int) DB::table('cms_pages')->where('slug', $slug)->value('page_id');
            foreach ($fields as $key => $value) {
                DB::table('cms_fields')->updateOrInsert(
                    ['page_id' => $pageId, 'field_key' => $key],
                    ['field_value' => $value, 'media_id' => null, 'media_aspect_ratio' => '16:9', 'media_fit_mode' => 'cover', 'deleted_at' => null, 'deleted_by' => null, 'deletion_reason' => null]
                );
            }
        }

        $items = [
            ['services','service','Pengadaan Perangkat','Hardware dan perangkat pendukung','Pengadaan PC, laptop, printer, projector, CCTV, dan perangkat jaringan.',1],
            ['services','service','Instalasi dan Integrasi','Infrastruktur teknologi','Pekerjaan instalasi, konfigurasi, dan integrasi sistem sesuai kebutuhan.',2],
            ['services','service','Maintenance','Dukungan berkala','Pemeriksaan, perawatan, dan pemeliharaan perangkat maupun infrastruktur.',3],
            ['projects','project','Implementasi Perangkat Kantor','Pengadaan dan instalasi','Contoh konten portofolio untuk kebutuhan pengembangan CMS.',1],
            ['about-us','hardware','Paket hardware','','PC, laptop, notebook, dan server (HP, Dell, Lenovo, Asus, Epson, Acer, MSI, dll).',1],
            ['about-us','hardware','CCTV','','Hikvision, Dahua, Uniview, dll.',2],
            ['about-us','hardware','Printer','','HP, Epson, Canon, dll.',3],
            ['about-us','hardware','Projector','','InFocus, Epson, NEC, dll.',4],
            ['about-us','service','Pengadaan Perangkat','Hardware dan perangkat pendukung','Pengadaan PC, laptop, printer, projector, CCTV, dan perangkat jaringan.',1],
            ['about-us','service','Instalasi dan Integrasi','Infrastruktur teknologi','Pekerjaan instalasi, konfigurasi, dan integrasi sistem sesuai kebutuhan.',2],
            ['about-us','service','Jaringan gas medis & nurse call','Instalasi jasa','Jaringan gas medis dan sistem nurse call untuk fasilitas kesehatan.',3],
            ['about-us','service','Mekanikal listrik tegangan rendah','Instalasi jasa','Instalasi listrik tegangan rendah untuk bangunan komersial.',4],
            ['about-us','service','Jaringan networking (CCTV, access door, dll)','Instalasi jasa','Jaringan CCTV, access door, dan sistem keamanan terintegrasi.',5],
            ['about-us','service','Jaringan telekomunikasi','Instalasi jasa','Instalasi jaringan telekomunikasi dan fiber optik.',6],
            ['about-us','service','Hardware','Perangkat keras','Paket hardware: PC, laptop, server, CCTV (Hikvision, Dahua, Uniview), printer (HP, Epson, Canon), projector (InFocus, Epson, NEC).',7],
            ['about-us','service','Maintenance','Perawatan berkala','Layanan perawatan berkala agar perangkat dan instalasi tetap berfungsi optimal.',8],
        ];
        foreach ($items as [$slug, $section, $title, $subtitle, $description, $order]) {
            $pageId = (int) DB::table('cms_pages')->where('slug', $slug)->value('page_id');
            DB::table('cms_items')->updateOrInsert(
                ['page_id' => $pageId, 'section_key' => $section, 'title' => $title],
                ['media_id' => null, 'subtitle' => $subtitle, 'item_value' => '', 'meta' => '', 'description' => $description, 'sort_order' => $order, 'is_active' => true, 'media_aspect_ratio' => '16:9', 'media_fit_mode' => 'cover', 'deleted_at' => null, 'deleted_by' => null, 'deletion_reason' => null]
            );
        }

        $partnerData = [
            ['PT Mitra Teknologi', 'demo/cms/partner-technology.svg', 'https://example.com', 1],
            ['Solusi Jaringan Nusantara', 'demo/cms/partner-network.svg', 'https://example.org', 2],
        ];
        foreach ($partnerData as [$name, $path, $website, $order]) {
            $mediaId = (int) DB::table('cms_media')->where('file_path', $path)->value('media_id');
            DB::table('cms_partners')->updateOrInsert(
                ['partner_name' => $name],
                ['media_id' => $mediaId, 'category_id' => null, 'website_url' => $website, 'display_order' => $order, 'is_active' => true, 'deleted_at' => null, 'deleted_by' => null, 'deletion_reason' => null]
            );
        }
    }
}