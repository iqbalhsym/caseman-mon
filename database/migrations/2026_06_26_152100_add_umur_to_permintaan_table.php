<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('permintaan', function (Blueprint $table) {
            if (!Schema::hasColumn('permintaan', 'umur')) {
                $table->string('umur')->nullable()->after('nama');
            }
        });
    }

    public function down(): void {
        Schema::table('permintaan', function (Blueprint $table) {
            $table->dropColumn('umur');
        });
    }
};
