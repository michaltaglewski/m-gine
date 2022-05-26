<?php

namespace mgine\web;

class HttpException extends \Exception
{
    public $statusCode;

    /**
     * @param $status
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($status, string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        $this->statusCode = $status;
        parent::__construct($message, $code, $previous);
    }

}