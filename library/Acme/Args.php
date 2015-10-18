<?php

namespace Acme;

/**
* Class for parsing and checking cmd-line args
*/
class Args
{
    public $argv;

    function __construct()
    {
        global $argv;
        $this->argv = $argv;
    }

    public function check()
    {
        if (php_sapi_name() !== 'cli') {
            throw new \Exception("Run this script with your terminal. This not a php application for your webserver.", 2);
        }

        if (!isset($this->argv[1]) || empty($this->argv[1])) {
            throw new \Exception("No argument given", 1);
        }
    }

    public function process()
    {
        $arg2 = false;
        if (isset($this->argv[2])) {
            if (trim($this->argv[2]) == '--copy') {
                $arg2 = 'copy';
            } else if (trim($this->argv[2]) == '--only-uri') {
                $arg2 = 'only-uri';
            } else {
                throw new \Exception("No valid second argument", 3);
                
            }
        }

        if (strpos($this->argv[1], 'youtube.com') === false) {
            return [$this->argv[1], $arg2];
        }

        $urlParts = parse_url($this->argv[1], PHP_URL_QUERY);
        parse_str($urlParts, $query);
        return [$query['list'], $arg2];
    }
}