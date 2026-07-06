<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

use App\Models\Permintaan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('permintaan:purge-old', function () {
    $count = Permintaan::where('created_at', '<', now()->subMonths(6))->delete();
    $this->info("Successfully purged {$count} permintaan records older than 6 months.");
})->purpose('Purge permintaan records older than 6 months');

Schedule::command('permintaan:purge-old')->daily();
