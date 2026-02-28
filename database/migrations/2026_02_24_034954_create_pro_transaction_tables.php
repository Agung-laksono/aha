<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('akun_kas', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('kode')->unique();
            $table->decimal('saldo_awal', 15, 2)->default(0);
            $table->decimal('saldo_saat_ini', 15, 2)->default(0);
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });

        Schema::table('pembelians', function (Blueprint $table) {
            $table->decimal('biaya_ongkir', 15, 2)->default(0);
            $table->decimal('biaya_lain', 15, 2)->default(0);
            $table->string('metode_pembayaran')->default('Cash'); // Cash, Credit/Termin
            $table->string('status_pembayaran')->default('Unpaid'); // Unpaid, Partial, Paid
            $table->date('jatuh_tempo')->nullable();
        });

        Schema::create('pembayaran_pembelian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pembelian_id')->constrained('pembelians')->cascadeOnDelete();
            $table->foreignId('akun_kas_id')->constrained('akun_kas');
            $table->decimal('jumlah_bayar', 15, 2);
            $table->date('tanggal_bayar');
            $table->string('referensi_bank')->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });

        Schema::create('dokumen_pembelian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pembelian_id')->constrained('pembelians')->cascadeOnDelete();
            $table->string('file_path');
            $table->string('nama_file');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dokumen_pembelian');
        Schema::dropIfExists('pembayaran_pembelian');
        Schema::table('pembelians', function (Blueprint $table) {
            $table->dropColumn(['biaya_ongkir', 'biaya_lain', 'metode_pembayaran', 'status_pembayaran', 'jatuh_tempo']);
        });
        Schema::dropIfExists('akun_kas');
    }
};
