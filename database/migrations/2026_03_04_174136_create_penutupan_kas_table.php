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
        Schema::create('penutupan_kas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('akun_kas_id')->constrained('akun_kas')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->date('tanggal');
            $table->decimal('saldo_aplikasi', 15, 2);
            $table->decimal('saldo_fisik', 15, 2);
            $table->decimal('selisih', 15, 2);
            $table->string('foto_bukti_fisik')->nullable();
            $table->text('keterangan')->nullable();
            $table->enum('status', ['balanced', 'mismatch'])->default('balanced');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penutupan_kas');
    }
};
