<?php

namespace Database\Seeders;

use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SampleTeamsAndStaffSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Tentukan Admin Utama (Jika belum ada, buat baru)
        $admin = User::firstOrCreate(
        ['email' => 'admin@example.com'],
        [
            'name' => 'Super Administrator',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]
        );

        $cabangs = [
            'Toko Pusat',
            'Cabang Madiun',
            'Cabang Surabaya'
        ];

        $staffNames = [
            'Toko Pusat' => ['Agus (Gudang)', 'Budi (Pembelian)', 'Citra (Keuangan)'],
            'Cabang Madiun' => ['Dani (Gudang)', 'Eko (Pembelian)', 'Fina (Keuangan)'],
            'Cabang Surabaya' => ['Gani (Gudang)', 'Hadi (Pembelian)', 'Ira (Keuangan)'],
        ];

        foreach ($cabangs as $index => $namaCabang) {
            // A. Buat Tim (Cabang)
            $team = Team::where('name', $namaCabang)->first();
            if (!$team) {
                $team = Team::forceCreate([
                    'user_id' => $admin->id,
                    'name' => $namaCabang,
                    'personal_team' => false,
                ]);
            }

            // Jadikan admin current team ke salah satu jika belum
            if ($index === 0 && !$admin->current_team_id) {
                $admin->current_team_id = $team->id;
                $admin->save();
            }

            // Set Permissions Scope ke Team ini
            setPermissionsTeamId($team->id);

            // B. Buat 3 Staf untuk Cabang Ini
            $staffGudang = $this->createStaff($staffNames[$namaCabang][0]);
            $staffPembelian = $this->createStaff($staffNames[$namaCabang][1]);
            $staffKeuangan = $this->createStaff($staffNames[$namaCabang][2]);

            // C. Masukkan Staf ke Tim sebagai 'member'
            $this->attachToTeam($team, $staffGudang);
            $this->attachToTeam($team, $staffPembelian);
            $this->attachToTeam($team, $staffKeuangan);

            // D. Berikan Izin Spesifik (Sesuai Panduan/Kasus Lapangan)

            // -- STAF GUDANG --
            // Boleh terima pembelian, view_stok, view_log_stok
            $staffGudang->givePermissionTo([
                'terima_pembelian',
                'view_stok',
                'view_log_stok',
                'manage_transfer_stok',
                'manage_gudang'
            ]);

            // -- STAF PEMBELIAN --
            // Boleh buat PO, manage vendor, manage barang
            $staffPembelian->givePermissionTo([
                'tambah_barang',
                'edit_barang',
                'hapus_barang',
                'manage_kategori',
                'manage_satuan',
                'manage_vendor',
                'create_pembelian',
                'view_riwayat_pembelian',
                'view_pembelian_harga'
            ]);

            // -- STAF KEUANGAN --
            // Boleh bayar hutang, mutasi kas, view neraca
            $staffKeuangan->givePermissionTo([
                'view_hutang',
                'bayar_pembelian',
                'view_mutasi_kas',
                'create_mutasi_kas',
                'update_mutasi_kas',
                'create_transfer_kas',
                'update_transfer_kas',
                'manage_kas',
                'create_akun_kas',
                'update_akun_kas',
                'view_audit_log'
            ]);
        }

        $this->command->info('3 Cabang beserta 9 Staf berhasil dibuat dan dikonfigurasi izinnya!');
    }

    private function createStaff(string $name)
    {
        $email = Str::slug($name) . '@example.com';
        return User::firstOrCreate(
        ['email' => $email],
        [
            'name' => $name,
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]
        );
    }

    private function attachToTeam(Team $team, User $user)
    {
        if (!$team->users->contains($user)) {
            $team->users()->attach($user, ['role' => 'member']);
        }

        // Pastikan current_team_id terisi
        if (!$user->current_team_id) {
            $user->current_team_id = $team->id;
            $user->save();
        }
    }
}
