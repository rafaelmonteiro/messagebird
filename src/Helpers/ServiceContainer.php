<?php

namespace Helpers;
use Helpers\Config;

class ServiceContainer
{
    private $arguments;

    public function __construct() 
    {
        $this->services  = Config::getServices();
        $this->arguments = [];    
    }

    public function get($service)
    {
        $service     = $this->services[$service];
        $reflection  = new \ReflectionClass($service["class"]);

        foreach ($service["arguments"] as $arg) {
            $this->arguments[] = new $arg();
        }
        return $reflection->newInstanceArgs($this->arguments);
    }
}