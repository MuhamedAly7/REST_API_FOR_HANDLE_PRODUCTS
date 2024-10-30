<?php

class ErrorHandler
{
    public static function handleException(Throwable $exception): void
    {
        http_response_code(500);
        echo json_encode([
            "code" => $exception->getCode(),
            "message" => $exception->getMessage(),
            "line" => $exception->getLine(),
            "file" => $exception->getFile()
        ]);
    }
    
    public static function handleError($errno, $errstr)
    {
        http_response_code(500);
        echo json_encode([
            "code" => $errno,
            "message" => $errstr
        ]);
    }
}