<?php

namespace Controllers;

class BalancesController extends BaseController
{
    private $ServiceContainer;
    private $response;
    private $error;
    
    public function __construct()
    {
	    parent::__construct();
        $this->ServiceContainer = $this->container;        
        $this->response         = $this->ServiceContainer->get('JSONResponse');
        $this->error            = $this->ServiceContainer->get('ExceptionHandler');
    }

    public function getBalance()
    {
      $messaging = $this->ServiceContainer->get('Balance')->use('messageBird');
      $result = $messaging->getBalance($this->error);
      $this->response->sendFromObject($result);
    }

}