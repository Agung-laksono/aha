<?php

namespace App\Providers;

use App\Actions\Jetstream\DeleteUser;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Laravel\Jetstream\Jetstream;

class JetstreamServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configurePermissions();

        Jetstream::createTeamsUsing(\App\Actions\Jetstream\CreateTeam::class);
        Jetstream::deleteUsersUsing(DeleteUser::class);

        Vite::prefetch(concurrency: 3);
    }

    /**
     * Configure the permissions that are available within the application.
     */
    protected function configurePermissions(): void
    {
        Jetstream::defaultApiTokenPermissions(['read']);

        Jetstream::role('admin', 'Administrator / Manajer', [
            'create',
            'read',
            'update',
            'delete',
            'cancel',
            'return',
            'manage_kas',
            'manage_users',
            'receive_items'
        ])->description('Akses penuh ke seluruh sistem, master data, pengaturan kas, retur, dan manajemen pengguna.');

        Jetstream::role('finance', 'Keuangan', [
            'read',
            'manage_kas'
        ])->description('Mengelola mutasi kas, saldo awal, dan melakukan pembayaran tagihan.');

        Jetstream::role('logistik', 'Kepala / Staf Gudang', [
            'read',
            'receive_items'
        ])->description('Menerima suplai fisik barang (Receiving) ke daftar gudang yang ditugaskan.');

        Jetstream::role('editor', 'Purchasing / Admin Input', [
            'create',
            'read',
            'update'
        ])->description('Membuat Nota PO, menambah/mengedit data Barang dan Vendor.');

        Jetstream::role('sales', 'Kasir POS', [
            'read',
            'create_sales'
        ])->description('Melayani penjualan pelanggan melalui sistem Point of Sale (POS).');

        Jetstream::role('member', 'Auditor / Read-Only', [
            'read',
        ])->description('Hanya dapat melihat laporan, riwayat transaksi, dan sisa stok barang.');
    }
}
