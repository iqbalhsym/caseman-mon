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
            if (!Schema::hasColumn('permintaan', 'nomor')) {
                $table->string('nomor')->nullable()->after('user_id');
            }
            if (!Schema::hasColumn('permintaan', 'tanggal')) {
                $table->date('tanggal')->nullable()->after('nomor');
            }
            if (!Schema::hasColumn('permintaan', 'jam')) {
                $table->string('jam')->nullable()->after('tanggal');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('permintaan', function (Blueprint $table) {
            $table->dropColumn(['nomor', 'tanggal', 'jam']);
        });
    }
};
