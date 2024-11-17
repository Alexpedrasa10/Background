<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Console\BackgroundJobRunner;
use App\Helpers\Background;

class RunJob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run:job {class} {method} {params?} {retries?} {delay?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Execute jobs';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $attempt = 0;
        $retryAttempts = $this->argument('retries') ?? 3;
        $delay = $this->argument('delay') ?? 0;

        do {
            
            if ($attempt != 0) {
                sleep($delay); 
            }

            $class = $this->argument('class');
            $method = $this->argument('method');
            $params = $this->argument('params');

            if ($params) {
                
                $params = json_decode($params, true);

                if (json_last_error() !== JSON_ERROR_NONE) {
                    $this->error('Invalid JSON format for parameters.');
                    return 1;
                }
            }

            try {

                BackgroundJobRunner::run($class, $method, $params);
                $this->info("Job $class::$method execute was succesfull..");
                return 1;
                
            } catch (\Exception $e) {
                $attempt++;
                $this->error("Error: " . $e->getMessage());
            }



        } while ($attempt < $retryAttempts);
    
        Background::log("Job fails after $attempt - $class::$method", 'error');       
    }
}
