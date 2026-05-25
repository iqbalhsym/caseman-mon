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
        DB::statement('ALTER TABLE obats ALTER COLUMN nama_generik TYPE text');
        DB::statement('ALTER TABLE obats ALTER COLUMN nama_item TYPE text');
        DB::statement('ALTER TABLE obats ALTER COLUMN f_nf TYPE text');
        DB::statement('ALTER TABLE obats ALTER COLUMN kode_item TYPE text');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('ALTER TABLE obats ALTER COLUMN nama_generik TYPE character varying(255)');
        DB::statement('ALTER TABLE obats ALTER COLUMN nama_item TYPE character varying(255)');
        DB::statement('ALTER TABLE obats ALTER COLUMN f_nf TYPE character varying(255)');
        DB::statement('ALTER TABLE obats ALTER COLUMN kode_item TYPE character varying(255)');
    }
};
