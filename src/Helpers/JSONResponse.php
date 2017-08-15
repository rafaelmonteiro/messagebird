<?php

namespace Helpers;
use StdClass;

class JSONResponse
{
    protected $response;

    const JSON_STRUCTURE_STRING_START = '{"data":';
    const JSON_STRUCTURE_STRING_END  = '}';

    public function __construct(){}

    private function _jsonHeader(int $code = 200)
    {
        header("HTTP/1.1 {$code}");
        header('Content-Type: application/json');
    }

    public function sendFromString(string $response, $code = 200)
    {
        $this->_jsonHeader($code);
        echo $this->_composeString($response);
        die();
    }

    public function sendFromObject($response, $code = 200)
    {
        $this->_jsonHeader($code);
        echo $this->_composeObject($response);
        die();
    }

    public function _composeString($response)
    {
        return self::JSON_STRUCTURE_STRING_START . $response . self::JSON_STRUCTURE_STRING_END;
    }

    public function _composeObject($response)
    {
        $res = new StdClass();
        $res->data = $response;
        return json_encode($res);
    }
}