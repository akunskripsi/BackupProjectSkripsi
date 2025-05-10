<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\ImportPembeliCsv;
use App\Console\Commands\ImportProdukCsv;


class Kernel extends ConsoleKernel
{
    protected $commands = [
        ImportPembeliCsv::class,
        ImportProdukCsv::class,
    ];

    protected function schedule(Schedule $schedule): void
    {
        //
    }

    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');
        require base_path('routes/console.php');
    }
}
