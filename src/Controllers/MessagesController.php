<?php

namespace Controllers;

class MessagesController extends BaseController
{
    private $ServiceContainer;
    private $request;
    private $response;
    private $error;
    
    const FIELDS_ARE_MISSING_MESSAGE      = 'Some fields are missing';
    const INVALID_MESSAGE_TYPE            = 'Invalid message type, must be string';
    const INVALID_ORIGINATOR_TYPE         = 'Invalid originator type, must be (string) MessageBird';
    const INVALID_RECIPIENT_TYPE          = 'Invalid recipient type, must be integer';

    const INVALID_STRING_TYPE             = 'invalid_string_type';
    const INVALID_INTEGER_TYPE            = 'invalid_integer_type';
    const FIELDS_MISSING_CODE             = 'missing_fields';

    const RECIPIENT                       = 'recipient';
    const MESSAGE                         = 'message';
    const ORIGINATOR                      = 'originator';

    const ORIGINATOR_VALUE                = 'MessageBird';

    const INVALID_REQUEST_CODE            = 400;

    public function __construct()
    {
		    parent::__construct();
        $this->ServiceContainer = $this->container;        
        $this->request          = $this->ServiceContainer->get('JSONRequest')->data();
        $this->response         = $this->ServiceContainer->get('JSONResponse');
        $this->error            = $this->ServiceContainer->get('ExceptionHandler');
    }

    public function sendSmsMessage()
    {
       $this->_checkInput() ? : $this->error->formatJson(self::FIELDS_ARE_MISSING_MESSAGE, self::FIELDS_MISSING_CODE, self::INVALID_REQUEST_CODE);
       $this->_validateInputs();

       $messaging = $this->ServiceContainer->get('Messaging')->use('messageBird');

       $messaging->composeMessage(
           filter_var($this->request[self::MESSAGE], FILTER_SANITIZE_STRING),
           $this->request[self::RECIPIENT],
           $this->request[self::ORIGINATOR]
      );

       $result = $messaging->send($this->error);

       $this->response->sendFromObject($result);
    }

    private function _checkInput()
    {
       if(!@$this->request[self::RECIPIENT] 
            || !@$this->request[self::ORIGINATOR] 
            || !@$this->request[self::MESSAGE]
       ){
            return false;
       }

       return true;
    }

    private function _validateInputs()
    {
      foreach($this->request[self::RECIPIENT] as $recipient){
          if(!filter_var($recipient, FILTER_VALIDATE_FLOAT)){
              $this->error->formatJson(self::INVALID_RECIPIENT_TYPE, self::INVALID_INTEGER_TYPE, self::INVALID_REQUEST_CODE);
         } 
      }

      if($this->request[self::ORIGINATOR] !== self::ORIGINATOR_VALUE){
          $this->error->formatJson(self::INVALID_ORIGINATOR_TYPE, self::INVALID_STRING_TYPE, self::INVALID_REQUEST_CODE);
      }
    }

}
