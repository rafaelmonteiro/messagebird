<?php

namespace Controllers;

class MessagesController extends BaseController
{
    private $request;
    private $response;
    private $error;
    
    const RECIPIENT                       = 'recipient';
    const MESSAGE                         = 'message';
    const ORIGINATOR                      = 'originator';

    public function __construct()
    {
	    parent::__construct();
        $this->request          = $this->container->get('JSONRequest');
        $this->response         = $this->container->get('JSONResponse');
        $this->error            = $this->container->get('ExceptionHandler');
    }

    public function sendSmsMessage()
    {
        $messaging = $this->container->get('Messaging')->use('messageBird');

        $messaging->composeMessage(
           filter_var($this->request->get(self::MESSAGE), FILTER_SANITIZE_STRING),
           $this->request->get(self::RECIPIENT),
           $this->request->get(self::ORIGINATOR)
        );

        $result = $messaging->send($this->error);

        $this->response->sendFromObject($result);
    }

}
