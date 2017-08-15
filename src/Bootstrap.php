<?php

namespace App;

use Helpers\Router;

class Bootstrap
{
    protected $env;
    protected $debug;

    public function __construct($env = 'prod', $debug = false)
    {
        $this->env   = $env;
        $this->debug = $debug;
    }

    public function run()
    {
        $Router            = new Router();
        $Controller_Method = $Router->useController();

        return call_user_func_array(
            [
                new $Controller_Method[0](),
                $Controller_Method[1]
            ],
            []
       );
    }
}