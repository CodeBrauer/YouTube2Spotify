<?php

namespace Acme;

/**
* Pretty print & log!
*/
class Output
{

    public static function put($msg, $state = false)
    {
        $states = [
            'success' => "\033[32m ✔ \033[0m",
            'warning' => "\033[33m ! \033[0m",
            'fail'    => "\033[31m ✖ \033[0m",
        ];

        $add = ($state !== false && array_key_exists($state, $states)) ? $states[$state] : '';

        echo $add . $msg . PHP_EOL;
    }   

    public static function blankLine($c = 1)
    {
        for ($i=0; $i < 1; $i++) { 
            echo PHP_EOL;
        }
        # kacke -> braun und fest
    }
}