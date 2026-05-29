<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class PintFixCommand extends Command
{
    protected $signature = 'pint:fix {--file= : Fix specific file}';
    protected $description = 'Fix code style using Laravel Pint';

    public function handle()
    {
        $this->info('🔧 Fixing code style...');

        $path = base_path('vendor/bin/pint');
        if (DIRECTORY_SEPARATOR === '\\') {
            $path = base_path('vendor/bin/pint.bat');
        }

        $command = [$path];
        
        if ($file = $this->option('file')) {
            $command[] = $file;
        }

        $process = new Process($command);
        $process->run();

        $this->info($process->getOutput());
        
        if (str_contains($process->getOutput(), '✓')) {
            $this->info('✅ Code formatted successfully!');
        }

        return $process->getExitCode();
    }
}