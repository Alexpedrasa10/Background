<?php

namespace App\Helpers;

class Background
{
    function runBackgroundJob(string $class, string $method, array $params = [])
    {
        $command = sprintf(
            'php %s artisan job:execute "%s" "%s" "%s"',
            base_path(),
            $class,
            $method,
            escapeshellarg(json_encode($params))
        );

        // Soporte para diferentes sistemas operativos
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            pclose(popen("start /B $command", 'r'));
        } else {
            exec("$command > /dev/null 2>&1 &");
        }
    }

}

