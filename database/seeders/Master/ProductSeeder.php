<?php

namespace Database\Seeders\Master;

use Database\Seeders\Support\SeederSupport;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    use SeederSupport;

    public function run(): void
    {
        if (!$this->isDemoEnvironment()) return;

        // Harga modal demo mengikuti asumsi prototype: 72% dari harga jual.
        $products = [
            ['PRD-001','PC Desktop Acer Veriton X','Hardware','Barang','Unit',8750000,true,20],
            ['PRD-002','Laptop Lenovo ThinkPad E14','Hardware','Barang','Unit',13500000,true,15],
            ['PRD-003','Laptop Dell Latitude 3440','Hardware','Barang','Unit',12900000,true,10],
            ['PRD-004','Server Dell PowerEdge T150','Hardware','Barang','Unit',24500000,true,2],
            ['PRD-005','Printer HP LaserJet Pro M404dn','Printer','Barang','Unit',5200000,true,10],
            ['PRD-006','Printer Epson EcoTank L3250','Printer','Barang','Unit',2650000,true,10],
            ['PRD-007','Printer Canon imageCLASS MF445dw','Printer','Barang','Unit',6400000,true,5],
            ['PRD-008','Projector Epson EB-X06','Projector','Barang','Unit',6900000,true,8],
            ['PRD-009','Projector InFocus IN114xa','Projector','Barang','Unit',5800000,true,6],
            ['PRD-010','Projector NEC NP-M350X','Projector','Barang','Unit',8900000,true,4],
            ['PRD-011','Kamera CCTV Hikvision 2MP','CCTV & Networking','Barang','Unit',850000,false,24],
            ['PRD-012','NVR Hikvision 16 Channel','CCTV & Networking','Barang','Unit',3450000,false,5],
            ['PRD-013','Instalasi jaringan gas medis & nurse call','Instalasi Jasa','Jasa','Paket',145000000,false,0],
            ['PRD-014','Instalasi mekanikal listrik tegangan rendah','Instalasi Jasa','Jasa','Paket',68000000,false,0],
            ['PRD-015','Instalasi jaringan networking (CCTV, access door)','Instalasi Jasa','Jasa','Titik',350000,false,0],
            ['PRD-016','Instalasi jaringan telekomunikasi','Instalasi Jasa','Jasa','Paket',52000000,false,0],
            ['PRD-017','Instalasi & setup perangkat (PC, printer, projector)','Instalasi Jasa','Jasa','Unit',250000,false,0],
            ['PRD-018','Service & maintenance berkala','Maintenance','Jasa','Bulan',4500000,false,0],
        ];

        foreach ($products as [$sku, $name, $category, $type, $unit, $sellingPrice, $trackSerial, $minStock]) {
            $this->upsertRow('products', ['sku' => $sku], [
                'product_name' => $name,
                'category_id' => $this->categoryId(match ($category) {
                    'Hardware' => 'CAT-HW', 'Printer' => 'CAT-PRN', 'Projector' => 'CAT-PRJ',
                    'CCTV & Networking' => 'CAT-CCTV', 'Instalasi Jasa' => 'CAT-SVC', default => 'CAT-MNT',
                }),
                'product_type' => $type,
                'unit' => $unit,
                'selling_price' => $this->money($sellingPrice),
                'purchase_price' => $this->money(round($sellingPrice * 0.72, 2)),
                'min_stock' => number_format((float) $minStock, 3, '.', ''),
                'track_serial' => $trackSerial,
                'is_active' => true,
            ]);
        }
    }
}
