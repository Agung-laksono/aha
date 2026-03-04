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
        Schema::create('transfer_kas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengirim_akun_id')->constrained('akun_kas')->onDelete('cascade');
            $table->foreignId('penerima_akun_id')->constrained('akun_kas')->onDelete('cascade');
            $table->decimal('jumlah', 15, 2);
            $table->string('keterangan')->nullable();

            // Konfirmasi & Audit
            $table->enum('status', ['pending', 'completed', 'rejected'])->default('pending');
            $table->foreignId('pengirim_user_id')->nullable()->constrained('users')->onDelete('set null'); // Siapa yang inisiasi
            $table->foreignId('penerima_user_id')->nullable()->constrained('users')->onDelete('set null'); // Siapa yang konfirmasi (Approve/Reject)

            $table->timestamp('tanggal_transfer')->useCurrent();
            $table->timestamp('tanggal_konfirmasi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfer_kas');
    }
};
