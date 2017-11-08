<?php

namespace Services;
use \MessageBird\Objects\Message as MessageBirdMessage;

class Message extends MessageBirdMessage
{
    const ORIGINATOR_VALUE = 'MessageBird';

    private function _checkInput()
    {
        if(!$this->recipients){
            throw new \InvalidArgumentException("Recipients are missing", 400);
        }
        if(!$this->originator){
            throw new \InvalidArgumentException("Originator is missing", 400);
        }
        if(!$this->body){
            throw new \InvalidArgumentException("Message is missing", 400);
        }

        return true;
    }

    private function _validateInputs()
    {
        if(!is_array($this->recipients))
            throw new \InvalidArgumentException("Invalid recipient container, must be an array", 400);

        foreach($this->recipients as $recipient){
            if(!filter_var($recipient, FILTER_VALIDATE_FLOAT))
                throw new \InvalidArgumentException("Invalid recipient type, must be a valid phone number", 400);
        }

        if($this->originator !== self::ORIGINATOR_VALUE)
            throw new \InvalidArgumentException("Invalid originator type, must be (string) ".self::ORIGINATOR_VALUE, 400);

        return true;
    }

    public function validate()
    {
        if($this->_checkInput() && $this->_validateInputs())
            return true;
    }

}