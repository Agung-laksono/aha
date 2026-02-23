<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vendor;

class VendorSeeder extends Seeder
{
    public function run()
    {
        $vendors = [
            ['nama' => 'PT. Furniture Indonesia', 'kontak' => '08123456789', 'alamat' => 'Jl. Industri No. 10', 'province_id' => '31', 'regency_id' => '3171', 'district_id' => '317401', 'village_id' => '3174011001', 'tag' => 'Furniture'],
            ['nama' => 'CV. Kayu Maju', 'kontak' => '08123456788', 'alamat' => 'Jl. Kayu No. 5', 'province_id' => '32', 'regency_id' => '3273', 'tag' => 'Kayu'],
            ['nama' => 'Toko Elektronik Makmur', 'kontak' => '08123456787', 'alamat' => 'Kawasan Glodok Blok A', 'province_id' => '31', 'regency_id' => '3171', 'tag' => 'Elektronik'],
            ['nama' => 'Distributor Plastik Oke', 'kontak' => '08123456786', 'alamat' => 'Kawasan Industri Jababeka', 'province_id' => '32', 'regency_id' => '3275', 'tag' => 'Plastik'],
            ['nama' => 'Grosir Alat Tulis', 'kontak' => '08123456785', 'alamat' => 'Mangga Dua Lt. 3', 'province_id' => '31', 'regency_id' => '3171', 'tag' => 'ATK'],
            ['nama' => 'Supplier Kain Tex', 'kontak' => '08123456784', 'alamat' => 'Pasar Tanah Abang', 'province_id' => '31', 'regency_id' => '3171', 'tag' => 'Tekstil'],
            ['nama' => 'Indo Food Supply', 'kontak' => '08123456783', 'alamat' => 'Jl. Food No. 1', 'province_id' => '32', 'regency_id' => '3271', 'tag' => 'Makanan'],
            ['nama' => 'Global Tech Solutions', 'kontak' => '08123456782', 'alamat' => 'Sudirman Central Business District', 'province_id' => '31', 'regency_id' => '3174', 'tag' => 'Teknologi'],
        ];

        foreach ($vendors as $v) {
            Vendor::updateOrCreate(['nama' => $v['nama']], $v);
        }
    }
}
