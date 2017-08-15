<?php

namespace Services;
use Helpers\Config;

class Messaging
{
    public function __construct()
    {
        $this->accessKey  = Config::get('messagebird-api-key');
        $this->references = [ 'messageBird' => 'Services\MessagingMessageBird' ];
    }

    public function use(string $service)
    {
        return new $this->references[$service]($this->accessKey);
    }
}
