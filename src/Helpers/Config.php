<?php

namespace Helpers;

class Config
{
    public function __construct(){}

    public static function get($key)
    {
		$config = json_decode(file_get_contents(__DIR__ . '/../Config/config.json'), true);
		if (isset($config[$key])){
			return $config[$key];
		}
    }

    public static function getControllers()
    {
    	return json_decode(file_get_contents(__DIR__ . '/../Config/controllers.json'), true);
    }

    public static function getServices()
    {
    	return json_decode(file_get_contents(__DIR__ . '/../Config/services.json'), true);
    }
}