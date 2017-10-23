<?php

namespace Controllers;

class BalancesController extends BaseController
{
    private $response;
    private $error;
    
    public function __construct()
    {
	    parent::__construct();
        $this->response         = $this->container->get('JSONResponse');
        $this->error            = $this->container->get('ExceptionHandler');
    }

    public function getBalance()
    {
        $messaging = $this->container->get('Balance')->use('messageBird');
        $result = $messaging->getBalance($this->error);
        $this->response->sendFromObject($result);
    }

}