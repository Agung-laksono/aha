<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Province;
use App\Models\Regency;
use App\Models\District;
use App\Models\Village;

class RegionSeeder extends Seeder
{
    public function run()
    {
        $regions = [
            ['id' => '11', 'name' => 'ACEH'],
            ['id' => '12', 'name' => 'SUMATERA UTARA'],
            ['id' => '13', 'name' => 'SUMATERA BARAT'],
            ['id' => '14', 'name' => 'RIAU'],
            ['id' => '15', 'name' => 'JAMBI'],
            ['id' => '16', 'name' => 'SUMATERA SELATAN'],
            ['id' => '17', 'name' => 'BENGKULU'],
            ['id' => '18', 'name' => 'LAMPUNG'],
            ['id' => '31', 'name' => 'DKI JAKARTA'],
            ['id' => '32', 'name' => 'JAWA BARAT'],
            ['id' => '33', 'name' => 'JAWA TENGAH'],
            ['id' => '34', 'name' => 'DI YOGYAKARTA'],
            ['id' => '35', 'name' => 'JAWA TIMUR'],
            ['id' => '36', 'name' => 'BANTEN'],
            ['id' => '51', 'name' => 'BALI'],
            ['id' => '52', 'name' => 'NUSA TENGGARA BARAT'],
            ['id' => '53', 'name' => 'NUSA TENGGARA TIMUR'],
            ['id' => '61', 'name' => 'KALIMANTAN BARAT'],
            ['id' => '62', 'name' => 'KALIMANTAN TENGAH'],
            ['id' => '63', 'name' => 'KALIMANTAN SELATAN'],
            ['id' => '64', 'name' => 'KALIMANTAN TIMUR'],
            ['id' => '71', 'name' => 'SULAWESI UTARA'],
            ['id' => '72', 'name' => 'SULAWESI TENGAH'],
            ['id' => '73', 'name' => 'SULAWESI SELATAN'],
            ['id' => '74', 'name' => 'SULAWESI TENGGARA'],
            ['id' => '81', 'name' => 'MALUKU'],
            ['id' => '82', 'name' => 'MALUKU UTARA'],
            ['id' => '91', 'name' => 'PAPUA BARAT'],
            ['id' => '94', 'name' => 'PAPUA'],
        ];

        foreach ($regions as $region) {
            Province::updateOrCreate(['id' => $region['id']], ['name' => $region['name']]);
        }

        // DKI JAKARTA Cities
        $jakartaCities = [
            ['id' => '3101', 'name' => 'KABUPATEN ADM. KEPULAUAN SERIBU'],
            ['id' => '3171', 'name' => 'KOTA ADM. JAKARTA PUSAT'],
            ['id' => '3172', 'name' => 'KOTA ADM. JAKARTA UTARA'],
            ['id' => '3173', 'name' => 'KOTA ADM. JAKARTA BARAT'],
            ['id' => '3174', 'name' => 'KOTA ADM. JAKARTA SELATAN'],
            ['id' => '3175', 'name' => 'KOTA ADM. JAKARTA TIMUR'],
        ];
        foreach ($jakartaCities as $city) {
            Regency::updateOrCreate(['id' => $city['id']], ['province_id' => '31', 'name' => $city['name']]);
        }

        // JAWA BARAT Cities
        $jawaBaratCities = [
            ['id' => '3201', 'name' => 'KABUPATEN BOGOR'],
            ['id' => '3202', 'name' => 'KABUPATEN SUKABUMI'],
            ['id' => '3203', 'name' => 'KABUPATEN CIANJUR'],
            ['id' => '3204', 'name' => 'KABUPATEN BANDUNG'],
            ['id' => '3271', 'name' => 'KOTA BOGOR'],
            ['id' => '3273', 'name' => 'KOTA BANDUNG'],
            ['id' => '3275', 'name' => 'KOTA BEKASI'],
            ['id' => '3276', 'name' => 'KOTA DEPOK'],
        ];
        foreach ($jawaBaratCities as $city) {
            Regency::updateOrCreate(['id' => $city['id']], ['province_id' => '32', 'name' => $city['name']]);
        }

        // Districts & Villages for Tebet (DKI Jakarta Selatan)
        District::updateOrCreate(['id' => '317401'], ['regency_id' => '3174', 'name' => 'TEBET']);
        $tebetVillages = [
            ['id' => '3174011001', 'name' => 'TEBET BARAT'],
            ['id' => '3174011002', 'name' => 'TEBET TIMUR'],
            ['id' => '3174011003', 'name' => 'KEBON BARU'],
            ['id' => '3174011004', 'name' => 'BUKIT DURI'],
            ['id' => '3174011005', 'name' => 'MANGGARAI'],
            ['id' => '3174011006', 'name' => 'MANGGARAI SELATAN'],
            ['id' => '3174011007', 'name' => 'MENTENG DALAM'],
        ];
        foreach ($tebetVillages as $village) {
            Village::updateOrCreate(['id' => $village['id']], ['district_id' => '317401', 'name' => $village['name']]);
        }
    }
}
