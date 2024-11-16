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
    public static function runBackgroundJob(string $class, string $method, array $params = [])
    {

        if (!class_exists($class) || !method_exists($class, $method)) {
            throw new InvalidArgumentException("The specified class or method does not exist.");
        }

        $paramsJson = json_encode($params, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $escapedParams = escapeshellarg($paramsJson);

        $command = sprintf(
            'php artisan run:job "%s" "%s" %s > /dev/null 2>&1 &',
            $class,
            $method,
            $escapedParams
        );

        // For Windows, use `start` instead of `&`
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            $command = sprintf(
                'start /B php artisan job:run "%s" "%s" %s',
                $class,
                $method,
                $escapedParams
            );
        }

        shell_exec($command);
    }

}
