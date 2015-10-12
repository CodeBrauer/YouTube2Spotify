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
        $copy = false;
        if (isset($this->argv[2]) && trim($this->argv[2]) == '--copy') {
            $copy = true;
        }

        if (strpos($this->argv[1], 'youtube.com/playlist') === false) {
            return [$this->argv[1], $copy];
        }

        $urlParts = parse_url($this->argv[1], PHP_URL_QUERY);
        parse_str($urlParts, $query);
        return [$query['list'], $copy];
    }
}