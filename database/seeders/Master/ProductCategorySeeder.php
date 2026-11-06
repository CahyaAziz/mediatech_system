<?php

namespace Database\Seeders\Master;

use Database\Seeders\Support\SeederSupport;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductCategorySeeder extends Seeder
{
    use SeederSupport;

    public function run(): void
    {
        if (!$this->isDemoEnvironment()) return;

        $categories = [
            ['category_code' => 'CAT-HW', 'category_name' => 'Hardware'],
            ['category_code' => 'CAT-PRN', 'category_name' => 'Printer'],
            ['category_code' => 'CAT-PRJ', 'category_name' => 'Projector'],
            ['category_code' => 'CAT-CCTV', 'category_name' => 'CCTV & Networking'],
            ['category_code' => 'CAT-SVC', 'category_name' => 'Instalasi Jasa'],
            ['category_code' => 'CAT-MNT', 'category_name' => 'Maintenance'],
        ];

        foreach ($categories as $category) {
            DB::table('product_categories')->updateOrInsert(
                ['category_code' => $category['category_code']],
                [...$category, 'is_active' => true]
            );
        }
    }
}
