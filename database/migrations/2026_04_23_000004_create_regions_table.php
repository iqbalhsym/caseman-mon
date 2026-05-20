<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('provinsis', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->timestamps();
        });
        Schema::create('kotas', function (Blueprint $table) {
            $table->id();
            $table->string('provinsi_id')->nullable();
            $table->string('name')->nullable();
            $table->timestamps();
        });
        Schema::create('kecamatans', function (Blueprint $table) {
            $table->id();
            $table->string('kota_id')->nullable();
            $table->string('name')->nullable();
            $table->timestamps();
        });
        Schema::create('kelurahans', function (Blueprint $table) {
            $table->id();
            $table->string('kecamatan_id')->nullable();
            $table->string('name')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('provinsis');
        Schema::dropIfExists('kotas');
        Schema::dropIfExists('kecamatans');
        Schema::dropIfExists('kelurahans');
    }
};
