<?php

namespace Services;
use StdClass;

class MessagingMessageBird implements MessagingInterface
{
    /**
     * UDH Information Elements
     * (https://en.wikipedia.org/wiki/Concatenated_SMS)
     * 05 = Application port addressing scheme, 16 bit address  
     * 00 = Concatenated short messages, 8-bit reference number 
     * 03 = Length of the header, excluding the first two fields
     */
    const DEFAULT_UDH_HEADER              = '050003';
    const DATA_CODING                     = 'auto';
    const DATA_TYPE_BINARY                = 'binary';
    const MAX_SINGLE_MESSAGE_LEN          = 160;
    const SPLIT_CONCATENATED_MESSAGE_LEN  = 153;
    const INTERNAL_SERVER_ERROR           = 500;
    const SLEEP_SECONDS                   = 1;

    protected $message;
    protected $phoneNumbers;
    protected $originator;
    protected $isSingleMessage;

    public function __construct($messageBirdKey)
    {
        $this->MessageBird            = new \MessageBird\Client($messageBirdKey);
        $this->Message                = new \MessageBird\Objects\Message();
    }

    private function _createSMSMessage()
    {
        $this->Message->originator              = $this->originator;
        $this->Message->recipients              = $this->phoneNumbers;
        $this->Message->body                    = $this->message;
        $this->Message->datacoding              = self::DATA_CODING;
    }

    private function _createBinaryMessage($message, $udh)
    {
        $this->Message->originator              = $this->originator;
        $this->Message->recipients              = $this->phoneNumbers;
        $this->Message->body                    = $message;
        $this->Message->datacoding              = self::DATA_CODING;
        $this->Message->type                    = self::DATA_TYPE_BINARY;
        $this->Message->typeDetails             = new StdClass();
        $this->Message->typeDetails->udh        = $udh;
    }
    
    private function _createUDH($message)
    {
       $totalMsgParts     = ceil(strlen($message)/self::SPLIT_CONCATENATED_MESSAGE_LEN);
       $totalMsgPartsHex  = dechex($totalMsgParts);
       
       if(strlen($totalMsgPartsHex) == 1) $totalMsgPartsHex = "0".$totalMsgPartsHex;
       
       /* Removed these identifier codes due to encoding errors at mobile device
        * $identifierCode              = rand(0, 255);
        * $identifierCodeHex           = dechex($identifierCode);
        */
       $messageCharIndexStart       = 0;                   
       $UDH                         = [];
       $messagePart                 = '';
       $userHeader                  = self::DEFAULT_UDH_HEADER.$totalMsgPartsHex;

       for ($i = 1; $i <= $totalMsgParts; $i++) {
            $messagePart = substr($message, $messageCharIndexStart, self::SPLIT_CONCATENATED_MESSAGE_LEN);
            $messageCharIndexStart += self::SPLIT_CONCATENATED_MESSAGE_LEN;
            $currentMessagePartsNoHex = dechex($i);

            if (strlen($currentMessagePartsNoHex) == 1) $currentMessagePartsNoHex = "0".$currentMessagePartsNoHex;   

            array_push($UDH, ['userHeader' => $userHeader.$currentMessagePartsNoHex, 'message' => $messagePart]);
       }

       return $UDH;           
    }

    private function _isSingleMessage($message)
    {
        return strlen($message) < self::MAX_SINGLE_MESSAGE_LEN;
    }

    public function composeMessage(string $message, array $phoneNumbers, string $originator)
    {
      $this->message      = $message;
      $this->phoneNumbers = $phoneNumbers;
      $this->originator   = $originator;

      if($this->_isSingleMessage($message)){ 
          $this->isSingleMessage = true;
          return;
      }
    }

    public function send($errorHandler)
    {
        try {
            if($this->isSingleMessage){
                $this->_sleep(self::SLEEP_SECONDS);
                $this->_createSMSMessage();
                $result = $this->MessageBird->messages->create($this->Message);
            }else{
                $UDH = $this->_createUDH($this->message);

                foreach ($UDH as $body) {
                    $this->_sleep(self::SLEEP_SECONDS);
                    $this->_createBinaryMessage($body['message'], $body['userHeader']);
                    $result = $this->MessageBird->messages->create($this->Message);
                }
            }

            return $result;
        
        } catch (\MessageBird\Exceptions\BalanceException $e) {
            $errorHandler->formatJson('Not enough balance', 'internal_server_error', self::INTERNAL_SERVER_ERROR);
        } catch (\MessageBird\Exceptions\AuthenticateException $e) {
            $errorHandler->formatJson('Authentication is mandatory to perform API requests', 'internal_server_error', self::INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            $errorHandler->formatJson('Internal server error: '.$e->getMessage(), 'internal_server_error', self::INTERNAL_SERVER_ERROR);
        }
    }

    private function _sleep($seconds)
    {
      sleep($seconds);
    }

}
