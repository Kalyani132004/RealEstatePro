<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
| Closure-based Artisan commands. Scheduled tasks (e.g. cleaning up stale
| property views, sending digest emails) can be registered here in later
| phases via Schedule::command(...).
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');
