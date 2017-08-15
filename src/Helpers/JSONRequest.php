<?php

namespace Helpers;

class JSONRequest
{
    public function __construct(){}
    public function data()
    {
		return json_decode(file_get_contents('php://input'), true);
    }
}