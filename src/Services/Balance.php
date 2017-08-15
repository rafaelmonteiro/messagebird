<?php

namespace Services;
use Helpers\Config;

class Balance
{
    public function __construct()
    {
        $this->accessKey  = Config::get('messagebird-api-key');
        $this->references = [ 'messageBird' => 'Services\BalanceMessageBird' ];
    }

    public function use(string $service)
    {
        return new $this->references[$service]($this->accessKey);
    }
}
