<?php

namespace Helpers;
use Helpers\Config;

class Router
{
    protected $uri;
    protected $routes;

    const CONTROLLER_NAMESPACE  = 'Controllers';
    const NOTFOUND_CONTROLLER   = 'NotFoundController';

    public function __construct()
    {
        $this->uri = parse_url($_SERVER['REQUEST_URI']);
        $this->routes = Config::getControllers();
    }

    public function useController()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $route  = $this->routes[$this->uri['path']][$method];
        $formatedRoute = ($route) ? [self::CONTROLLER_NAMESPACE."\\".$route['controller'], $route['path']] : [self::CONTROLLER_NAMESPACE."\\".self::NOTFOUND_CONTROLLER, 'NotFoundMethod'];

        return $formatedRoute;
    }
}