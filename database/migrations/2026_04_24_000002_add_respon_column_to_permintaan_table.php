<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('permintaan', function (Blueprint $table) {
            if (!Schema::hasColumn('permintaan', 'tanggal_jam_respon')) {
                $table->dateTime('tanggal_jam_respon')->nullable()->after('status_angka');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permintaan', function (Blueprint $table) {
            $table->dropColumn('tanggal_jam_respon');
        });
    }
};
