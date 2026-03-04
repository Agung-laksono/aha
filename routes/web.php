<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::get('/inventory', function () {
        return view('inventory');
    })->name('inventory');

    Route::get('/print-po/{id}', function ($id) {
        $pembelian = \App\Models\Pembelian::with(['vendor', 'details.barang'])->findOrFail($id);
        return view('print-po', compact('pembelian'));
    })->name('print-po');

    Route::get('/print-transfer/{id}', function ($id) {
        $user = auth()->user();
        $transfer = \App\Models\StockTransfer::with(['gudangAsal', 'gudangTujuan', 'details.barang', 'user'])->findOrFail($id);

        // Security check: If not admin, must have access to either origin or destination warehouse
        if (!$user->hasTeamRole($user->currentTeam, 'admin')) {
            $accessibleIds = $user->accessibleGudangIds();
            if (!in_array($transfer->gudang_asal_id, $accessibleIds) && !in_array($transfer->gudang_tujuan_id, $accessibleIds)) {
                abort(403, 'Anda tidak memiliki akses ke surat jalan ini.');
            }
        }

        return view('print-transfer', compact('transfer'));
    })->name('print-transfer')->middleware('signed');

    Route::get('/activity-log', function () {
        return view('activity-log-page');
    })->name('activity-log');

    Route::get('/mutasi-stok', \App\Livewire\MutasiStok::class)
        ->name('mutasi-stok');

    Route::get('/keuangan', \App\Livewire\Keuangan::class)
        ->name('keuangan');

    Route::get('/panduan', function () {
        return view('panduan');
    })->name('panduan');

    // Admin Only User Management
    Route::get('/user-management', \App\Livewire\UserManagement::class)
        ->name('user-management')
        ->middleware([
            'auth:sanctum',
            config('jetstream.auth_session'),
            'verified',
        ]);
});
