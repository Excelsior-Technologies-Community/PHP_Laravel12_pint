<?php

use App\Console\Commands\PintCheckCommand;
use App\Console\Commands\PintFixCommand;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Register Pint commands
Artisan::command('pint:check', function () {
    $this->call(PintCheckCommand::class);
})->purpose('Check code style using Laravel Pint');

Artisan::command('pint:fix', function () {
    $this->call(PintFixCommand::class);
})->purpose('Fix code style using Laravel Pint');