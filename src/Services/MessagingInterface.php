<?php

namespace Services;

interface MessagingInterface
{
    public function composeMessage(
        string  $message, 
        array $phoneNumbers, 
        string  $originator 
   );

    public function send($errorHandler);
}
