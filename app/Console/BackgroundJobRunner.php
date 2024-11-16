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

            $class = "App\Jobs\{$className}";

            if (!class_exists($class)) {
                throw new \Exception("La clase $className no existe.");
            }
            if (!method_exists($class, $method)) {
                throw new \Exception("El método $method no existe en $className.");
            }

            $instance = new $className();
            $result = call_user_func_array([$instance, $method], $params);

            self::log("Job ejecutado con éxito: $className::$method", 'success');

            return $result;
        
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

