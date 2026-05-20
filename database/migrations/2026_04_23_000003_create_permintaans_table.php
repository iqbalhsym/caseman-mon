<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('permintaan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('lokasi_id')->nullable();
            $table->unsignedBigInteger('manager_id')->nullable();
            $table->string('uuid')->nullable()->unique();
            $table->string('no_rm')->nullable();
            $table->string('nama')->nullable();
            $table->string('jaminan')->nullable();
            $table->date('tanggal_masuk')->nullable();
            $table->string('ruangan')->nullable();
            $table->string('lantai')->nullable();
            $table->text('diagnosis')->nullable();
            $table->string('kategori')->nullable();
            $table->string('riwayat')->nullable();
            $table->text('keterangan')->nullable();
            $table->text('indikasi')->nullable();
            $table->string('status')->nullable()->default('menunggu');
            $table->integer('status_angka')->nullable()->default(0);
            $table->text('catatan_diterima')->nullable();
            $table->integer('jumlah_hari')->nullable();
            $table->date('tanggal_mulai_expired')->nullable();
            $table->date('tanggal_berakhir_expired')->nullable();
            $table->string('file')->nullable();
            $table->string('file2')->nullable();
            $table->string('file3')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('permintaan');
    }
};
