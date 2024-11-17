<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\File;
use Tests\TestCase;
use App\Helpers\Background;

class BackgroundRunTest extends TestCase
{

    public function test_run_background_job_sum_success(): void
    {
        $logFile = storage_path('logs/background_jobs_success.log');
        
        if (File::exists($logFile)) {
            File::delete($logFile);
        }

        Background::runBackgroundJob('App\Jobs\Maths', 'sum', [1, 6, 9, 10]);
        sleep(2);

        // Verify if logs exists
        $this->assertTrue(File::exists($logFile), 'El archivo de log no fue creado.');

        // Verify logs content
        $logContent = File::get($logFile);
        $this->assertStringContainsString('Job execute was succesfull.: App\Jobs\Maths::sum', $logContent, 'El resultado esperado no está en el log.');

        File::delete($logFile);
    }

    public function test_run_background_job_sum_error(): void
    {
        $logFile = storage_path('logs/background_jobs_error.log');

        // Ensure log file does not exist before running the job
        if (File::exists($logFile)) {
            File::delete($logFile);
        }

        // Run the background job with invalid data to trigger an error
        Background::runBackgroundJob('App\Jobs\Maths', 'sum', [1, 6, 9, "Fails"], 4, 1);
        sleep(4);

        // Verify if logs exist
        $this->assertTrue(File::exists($logFile), 'El archivo de log de errores no fue creado.');

        // Verify logs content
        $logContent = File::get($logFile);

        $this->assertStringContainsString('Error ejecutando App\Jobs\Maths::sum: Invalid parameter type: string', $logContent, 'El error esperado no está en el log.');
        $this->assertStringContainsString('Job fails after 4 - App\Jobs\Maths::sum', $logContent, 'Dont have retry attemps.');

        // Clean up
        File::delete($logFile);
    }

    public function test_run_background_job_multiply_success(): void
    {
        $logFile = storage_path('logs/background_jobs_success.log');
        
        if (File::exists($logFile)) {
            File::delete($logFile);
        }

        Background::runBackgroundJob('App\Jobs\Maths', 'multiply', [8, 5, 11, 10]);
        sleep(2);

        // Verify if logs exists
        $this->assertTrue(File::exists($logFile), 'The log file not exists.');

        // Verify logs content
        $logContent = File::get($logFile);
        $this->assertStringContainsString('Job execute was succesfull.: App\Jobs\Maths::multiply', $logContent, 'Result is not in the log file.');

        File::delete($logFile);
    }

    public function test_run_background_job_multiply_error(): void
    {
        $logFile = storage_path('logs/background_jobs_error.log');
        
        if (File::exists($logFile)) {
            File::delete($logFile);
        }

        Background::runBackgroundJob('App\Jobs\Maths', 'multiply', ['Fails'], 4, 1);
        sleep(4);

        // Verify if logs exists
        $this->assertTrue(File::exists($logFile), 'The log file not exists.');

        // Verify logs content
        $logContent = File::get($logFile);
        
        $this->assertStringContainsString('Error ejecutando App\Jobs\Maths::multiply', $logContent, 'Result is not in the log file.');
        $this->assertStringContainsString('Job fails after 4 - App\Jobs\Maths::multiply', $logContent, 'Dont have retry attemps.');

        File::delete($logFile);
    }

    public function test_run_background_job_subtract_success(): void
    {
        $logFile = storage_path('logs/background_jobs_success.log');
        
        if (File::exists($logFile)) {
            File::delete($logFile);
        }

        Background::runBackgroundJob('App\Jobs\Maths', 'subtract', [5,6], 4, 1);
        sleep(4);

        // Verify if logs exists
        $this->assertTrue(File::exists($logFile), 'The log file not exists.');

        // Verify logs content
        $logContent = File::get($logFile);

        $this->assertStringContainsString('Job execute was succesfull.: App\Jobs\Maths::subtract', $logContent, 'Result is not in the log file.');        

        File::delete($logFile);
    }

    public function test_run_background_job_subtract_error(): void
    {
        $logFile = storage_path('logs/background_jobs_error.log');
        
        if (File::exists($logFile)) {
            File::delete($logFile);
        }

        Background::runBackgroundJob('App\Jobs\Maths', 'subtract', ['Fails'], 4, 1);
        sleep(4);

        // Verify if logs exists
        $this->assertTrue(File::exists($logFile), 'The log file not exists.');

        // Verify logs content
        $logContent = File::get($logFile);

        $this->assertStringContainsString('Error ejecutando App\Jobs\Maths::subtract:', $logContent, 'Result is not in the log file.');

        $this->assertStringContainsString('Job fails after 4 - App\Jobs\Maths::subtract', $logContent, 'Dont have retry attemps.');

        File::delete($logFile);
    }
}

