<?php

namespace Controllers;

class NotFoundController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function NotFoundMethod()
    {
       $this->container->get('JSONResponse')->sendFromString('{"error_code": "Resource not found", "code": "resource_not_found"}', 404);
    }
}