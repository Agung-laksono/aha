<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create('id_ID');

        // 0. Pastikan ada User
        $user = \App\Models\User::firstOrCreate(
            ['email' => 'a@a.com'],
            [
                'name' => 'agung',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
            ]
        );
        $userId = $user->id;

        // 1. Buat Satuan
        $satuans = [];
        $satuanData = [
            ['nama' => 'Pcs', 'kode' => 'PCS', 'deskripsi' => 'Pieces'],
            ['nama' => 'Box', 'kode' => 'BOX', 'deskripsi' => 'Box Container'],
            ['nama' => 'Unit', 'kode' => 'UNT', 'deskripsi' => 'Unit per Item'],
            ['nama' => 'Kilogram', 'kode' => 'KG', 'deskripsi' => 'Weight Kilogram'],
        ];
        foreach ($satuanData as $s) {
            $satuans[] = \App\Models\Satuan::firstOrCreate(['kode' => $s['kode']], $s);
        }

        // 2. Buat Kategori & SubKategori
        $categories = [
            'Elektronik' => ['Laptop', 'Smartphone', 'Aksesoris'],
            'Fashion' => ['Pakaian Pria', 'Pakaian Wanita', 'Sepatu'],
            'Rumah Tangga' => ['Dapur', 'Ruang Tamu', 'Kamar Tidur'],
            'Otomotif' => ['Suku Cadang', 'Perawatan', 'Aksesoris Mobil'],
            'Kesehatan' => ['Suplemen', 'Alat Medis', 'Skincare'],
        ];

        $subKategoriIds = [];
        foreach ($categories as $catName => $subs) {
            $kategori = \App\Models\Kategori::firstOrCreate(
                ['nama' => $catName],
                ['kode' => strtoupper(substr($catName, 0, 3)), 'deskripsi' => "Kategori $catName"]
            );

            foreach ($subs as $subName) {
                $sub = \App\Models\SubKategori::firstOrCreate(
                    ['nama' => $subName, 'kategori_id' => $kategori->id],
                    ['kode' => strtoupper(substr($subName, 0, 3)) . rand(10, 99), 'deskripsi' => "Sub Kategori $subName"]
                );
                $subKategoriIds[] = $sub->id;
            }
        }

        // 3. Buat 500 Produk
        $this->command->info('Sedang membuat 500 data produk dummy...');

        for ($i = 0; $i < 500; $i++) {
            $namaProduk = $faker->unique()->words(rand(2, 4), true);
            $subKatId = $faker->randomElement($subKategoriIds);
            $satId = $faker->randomElement($satuans)->id;

            $barang = \App\Models\Barang::create([
                'nama' => ucwords($namaProduk),
                'sku' => 'PROD-' . strtoupper($faker->bothify('??###-####')),
                'deskripsi' => $faker->sentences(3, true),
                'sub_kategori_id' => $subKatId,
                'satuan_id' => $satId,
                'kategori_id' => \App\Models\SubKategori::find($subKatId)->kategori_id,
            ]);

            // Harga
            $hargaBeli = rand(10000, 5000000);
            $hargaJual = $hargaBeli * (1 + (rand(10, 50) / 100));

            \App\Models\HargaBeli::create([
                'barang_id' => $barang->id,
                'harga' => $hargaBeli,
                'user_id' => $userId,
            ]);

            \App\Models\HargaJual::create([
                'barang_id' => $barang->id,
                'harga' => $hargaJual,
                'user_id' => $userId,
            ]);

            // Gambar Dummy (1-4 gambar per produk)
            $jumlahGambar = rand(1, 4);
            $imagePlaceholders = [
                'https://flowbite.s3.amazonaws.com/blocks/e-commerce/imac-front.svg',
                'https://flowbite.s3.amazonaws.com/blocks/e-commerce/iphone-14-pro.svg',
                'https://flowbite.s3.amazonaws.com/blocks/e-commerce/ipad-pro.svg',
                'https://flowbite.s3.amazonaws.com/blocks/e-commerce/macbook-pro.svg',
                'https://flowbite.s3.amazonaws.com/blocks/e-commerce/ps5.svg',
                'https://flowbite.s3.amazonaws.com/blocks/e-commerce/apple-watch.svg',
            ];

            for ($j = 0; $j < $jumlahGambar; $j++) {
                // Catatan: Karena ini seeder dummy, kita gunakan URL luar sebagai path simulasi
                // Di aplikasi nyata, kita akan mengunduh atau menggunakan path lokal
                \App\Models\GambarBarang::create([
                    'barang_id' => $barang->id,
                    'path' => $faker->randomElement($imagePlaceholders), // Menggunakan URL luar untuk simulasi display
                    'gambar_utama' => $j === 0,
                ]);
            }
        }

        $this->command->info('500 data produk berhasil dibuat!');
    }
}
