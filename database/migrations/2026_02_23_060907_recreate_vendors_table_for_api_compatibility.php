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
        Schema::dropIfExists('pembelian_details');
        Schema::dropIfExists('pembelians');
        Schema::dropIfExists('vendors');

        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('kontak')->nullable();
            $table->string('province_id', 20)->nullable();
            $table->string('regency_id', 20)->nullable();
            $table->string('district_id', 20)->nullable();
            $table->string('village_id', 20)->nullable();
            $table->text('alamat')->nullable();
            $table->string('tag')->nullable();
            $table->string('gambar')->nullable();
            $table->timestamps();
        });

        Schema::create('pembelians', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_nota')->unique();
            $table->foreignId('vendor_id')->constrained()->cascadeOnDelete();
            $table->integer('total_harga');
            $table->string('status');
            $table->timestamp('tanggal');
            $table->timestamps();
        });

        Schema::create('pembelian_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pembelian_id')->constrained()->cascadeOnDelete();
            $table->foreignId('barang_id')->constrained()->cascadeOnDelete();
            $table->integer('qty_pesan');
            $table->integer('qty_terima')->default(0);
            $table->integer('harga_beli');
            $table->foreignId('gudang_id')->constrained()->cascadeOnDelete();
            $table->string('status_item');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
