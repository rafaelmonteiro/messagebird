<?php

namespace App;

use Helpers\Router;
use Helpers\ExceptionHandler;

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
        try {
            $router           = new Router();
            $controllerMethod = $router->useController();

            return call_user_func_array(
                [
                    new $controllerMethod[0](),
                    $controllerMethod[1]
                ],
                []
            );
        } catch (\Throwable $e) {
            $exceptionHandler = new ExceptionHandler();
            $exceptionHandler->handle($e);
        }
    }
}