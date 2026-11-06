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
                'about_description' => 'Meditech menyediakan solusi teknologi informasi, pengadaan perangkat, instalasi, serta dukungan pemeliharaan.',
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