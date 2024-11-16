<?php

namespace App\Console;

class BackgroundJobRunner
{

    /**
     * Function global to run job dynamicly
     * 
     * @param string $className
     * @param string $method
     * @param array $params 
     * 
     */
    public static function run(string $className, string $method, array $params = []) :mixed
    {
        try {

            $class = "App\\Jobs\\$className";

            if (!class_exists($class)) {
                throw new \Exception("The class $className not exists.");
            }

            if (!method_exists($class, $method)) {
                throw new \Exception("The method $method not exists in $className.");
            }

            dump(config('background_jobs.allowed_classes'));
            if (!in_array($class, config('background_jobs.allowed_classes'))) {
                throw new \Exception("The class $className is unathorized.");
            }

            $instance = new $class();

            self::log("Job ejecutado con éxito: $className::$method", 'success');
            return $instance->$method($params);

        } catch (\Exception $e) {

            self::log("Error ejecutando $className::$method: " . $e->getMessage(), 'error');
            throw $e;
        }
    }

    /**
     * Create log for background jobs
     * 
     * @param string $message
     * @param string $type
     * @return void
     */
    private static function log(string $message, string $type = 'info') :void
    {
        $logFile = storage_path("logs/background_jobs_$type.log");
        file_put_contents($logFile, '[' . now() . "] $message" . PHP_EOL, FILE_APPEND);
    }
}

