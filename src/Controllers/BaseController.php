<?php

namespace Controllers;
use Helpers\ServiceContainer;

abstract class BaseController{

    public $container;

    public function __construct(){
        $this->container = new ServiceContainer();
    }

}
