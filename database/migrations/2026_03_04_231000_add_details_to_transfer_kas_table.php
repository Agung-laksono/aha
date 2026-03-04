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
        Schema::table('transfer_kas', function (Blueprint $col) {
            $col->string('foto_bukti')->nullable()->after('keterangan');
            $col->text('alasan_penolakan')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transfer_kas', function (Blueprint $col) {
            $col->dropColumn(['foto_bukti', 'alasan_penolakan']);
        });
    }
};
