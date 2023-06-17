<?php

class ValidationException extends Exception
{
    public function __construct(array $message = null, int $code = 0, Exception $previous = null) {
        parent::__construct(json_encode($message), $code, $previous);
    }

    public function getArrayMessage($assoc = true) {
        return json_decode($this->getMessage(), $assoc);
    }
}