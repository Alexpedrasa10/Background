<?php

namespace App\Console;

use App\Helpers\Background;

class BackgroundJobRunner
{

    /**
     * Function global to run job dynamicly
     * 
     * @param string $class
     * @param string $method
     * @param array $params 
     * 
     */
    public static function run(string $class, string $method, array $params = []) :mixed
    {
        try {

            if (!class_exists($class)) {
                throw new \Exception("The class $class not exists.");
            }

            if (!method_exists($class, $method)) {
                throw new \Exception("The method $method not exists in $class.");
            }

            if (!in_array($class, config('background_jobs.allowed_classes'))) {
                throw new \Exception("The class $class is unathorized.");
            }

            $instance = new $class();

            Background::log("Job execute was succesfull.: $class::$method", 'success');
            return $instance->$method($params);

        } catch (\Exception $e) {

            Background::log("Error ejecutando $class::$method: " . $e->getMessage(), 'error');
            throw $e;
        }
    }
}

