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
        Schema::table('mutasi_kas', function (Blueprint $table) {
            $table->string('foto_bukti')->nullable()->after('jumlah');
            $table->boolean('is_verified')->default(false)->after('foto_bukti');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mutasi_kas', function (Blueprint $table) {
            $table->dropColumn(['foto_bukti', 'is_verified']);
        });
    }
};
