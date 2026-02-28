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

    Route::get('/activity-log', function () {
        return view('activity-log-page');
    })->name('activity-log');

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
