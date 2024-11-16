<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Console\BackgroundJobRunner;

class RunJob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run:job {class} {method} {params?}';

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
            $this->info("Job $class::$method ejecutado con éxito.");
            
        } catch (\Exception $e) {
            $this->error("Error: " . $e->getMessage());
        }
    }
}
