<?php

namespace Helpers;

class JSONRequest
{
    public function __construct(){}
    public function data()
    {
		return json_decode(file_get_contents('php://input'), true);
    }

    public function get($name)
    {
        $data = $this->data() ? : [];
        if(array_key_exists($name, $data))
            return $data[$name];

        throw new \InvalidArgumentException("Parameter '{$name}' not found", 400);
    }
}