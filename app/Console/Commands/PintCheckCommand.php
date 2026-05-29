<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class PintCheckCommand extends Command
{
    protected $signature = 'pint:check {--file= : Check specific file}';
    protected $description = 'Check code style using Laravel Pint';

    public function handle()
    {
        $this->info('🔍 Checking code style...');

        $path = base_path('vendor/bin/pint');
        if (DIRECTORY_SEPARATOR === '\\') {
            $path = base_path('vendor/bin/pint.bat');
        }

        $command = [$path, '--test', '-v'];
        
        if ($file = $this->option('file')) {
            $command[] = $file;
        }

        $process = new Process($command);
        $process->run();

        if ($process->isSuccessful()) {
            $this->info('✅ Code is clean!');
        } else {
            $this->error('⚠️ Code style issues found:');
        }

        $this->line($process->getOutput());
        
        return $process->getExitCode();
    }
}