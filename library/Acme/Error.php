<?php

namespace Acme;

/**
* Handing Errors
*/
class Error
{

    const DEBUG = true;

    public static function handle($e)
    {
        if (self::DEBUG) {
            echo "\033[31m ERROR: \033[0m" . PHP_EOL
            . "Message: " . $e->getMessage() . PHP_EOL
            . "   File: " . $e->getFile() . PHP_EOL
            . "   Line: " . $e->getLine() . PHP_EOL
            . "   Code: " . $e->getCode() . PHP_EOL;
        } else {
            echo "\033[31m ERROR: \033[0m" . $e->getMessage() . PHP_EOL;
        }
        exit(1);
    }

}