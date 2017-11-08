<?php

namespace Helpers;

class ExceptionHandler
{
    public function __construct(){}

    public function formatJson(string $message, string $code, $httpCode = 400)
    {
        header("HTTP/1.1 {$httpCode}");
        header('Content-Type: application/json');
        echo json_encode($this->_formatResponse($message, $code));
        die();
    }

    private function _formatResponse($message, $code)
    {
        return ['data'=> 
            [
                'error_message' => $message,
                'code' => $code,
            ]
        ];
    }

    public function handle(\Throwable $e)
    {
        $this->formatJson($e->getMessage(), $e->getCode());
    }
}