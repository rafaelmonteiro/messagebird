<?php

namespace Controllers;

class NotFoundController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->ServiceContainer = $this->container;
    }

    public function NotFoundMethod()
    {
       $this->ServiceContainer->get('JSONResponse')->sendFromString('{"error_code": "Resource not found", "code": "resource_not_found"}', 404);
    }
}