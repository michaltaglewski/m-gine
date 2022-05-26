<?php

namespace mgine\base;

class HeadersAlreadySentException extends \Exception
{
    public function __construct($file, $line)
    {
        $message = 'Headers already sent.'; // "Headers already sent in {$file} on line {$line}."
        parent::__construct($message);
    }
}