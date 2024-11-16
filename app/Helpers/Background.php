<?php

namespace App\Helpers;

use InvalidArgumentException;
class Background
{
    /**
     * Run a class method in the background.
     *
     * @param string $class Fully qualified class name.
     * @param string $method Method name to call.
     * @param array $params Parameters to pass to the method.
     * @throws InvalidArgumentException If the class or method does not exist.
     */
    public static function runBackgroundJob(string $className, string $method, array $params = [])
    {
        $class = "App\\Jobs\\$className";

        if (!class_exists($class) || !method_exists($class, $method)) {
            throw new InvalidArgumentException("The specified class or method does not exist.");
        }

        $command = sprintf(
            'php artisan run:job "%s" "%s" \'%s\' > /dev/null 2>&1 &',
            $className,
            $method,
            escapeshellarg(json_encode($params))  // Usa escapeshellarg para manejar los parámetros de manera segura
        );

        // For Windows, use `start` instead of `&`
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $command = sprintf(
                'start /B php artisan job:run "%s" "%s" \'%s\'',
                $className,
                $method,
                json_encode($params)
            );
        }

        shell_exec($command);
    }
}
