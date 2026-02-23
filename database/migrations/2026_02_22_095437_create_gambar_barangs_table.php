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
        Schema::create('gambar_barangs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barang_id')->constrained()->cascadeOnDelete();
            $table->string('path');
            $table->boolean('gambar_utama')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gambar_barangs');
    }
};
