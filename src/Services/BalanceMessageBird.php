<?php

namespace Services;

class BalanceMessageBird
{
    const INTERNAL_SERVER_ERROR = 500;

    public function __construct($messageBirdKey)
    {
        $this->MessageBird = new \MessageBird\Client($messageBirdKey);
    }

    public function getBalance($errorHandler)
    {
        try {
            return $this->MessageBird->balance->read();
        } catch (\MessageBird\Exceptions\AuthenticateException $e) {
            $errorHandler->formatJson('Can not perform API request without authentication', 'internal_server_error', self::INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            $errorHandler->formatJson('Internal server error: '.$e->getMessage(), 'internal_server_error', self::INTERNAL_SERVER_ERROR);
        }        
    }
}
