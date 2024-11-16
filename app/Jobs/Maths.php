<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class Maths implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function sum(array $params)
    {
        $result = 0;

        foreach ($params as $p) {
            
            if (!is_int($p)) {
                throw new \InvalidArgumentException("Invalid parameter type: " . gettype($p));
            }

            $result += $p;
        }
        dump($result);
        return $result;
    }

    public function subtract(array $params)
    {
        if (empty($params)) return 0;

        $result = array_shift($params);

        if (!is_int($result)) {
            throw new \InvalidArgumentException("Invalid parameter type: " . gettype($result));
        }

        foreach ($params as $p) {
            if (!is_int($p)) {
                throw new \InvalidArgumentException("Invalid parameter type: " . gettype($p));
            }
            $result -= $p;
        }

        return $result;
    }

    public function multiply(array $params)
    {
        if (empty($params)) return 0;

        $result = 1;

        foreach ($params as $p) {
            if (!is_int($p)) {
                throw new \InvalidArgumentException("Invalid parameter type: " . gettype($p));
            }
            $result *= $p;
        }

        return $result;
    }
}
