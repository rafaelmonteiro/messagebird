<?php

namespace Helpers;
use StdClass;

class ExceptionHandler
{
    public function __construct(){}

    public function formatJson(string $message, string $code, $httpCode = 400){
        header("HTTP/1.1 {$httpCode}");
        header('Content-Type: application/json');
        echo json_encode($this->_formatResponse($message, $code));
        die();
    }

    private function _formatResponse($message, $code){
        $error = new StdClass();
        $error->data = new StdClass();
        $error->data->error_message = $message;
        $error->data->code          = $code;
        return $error;
    }
}