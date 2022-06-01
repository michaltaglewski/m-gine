<?php

namespace mgine\console;

class UnknownCommandException extends \Exception
{
    /**
     * Construct the exception.
     *
     * @param string $route the route of the command that could not be found.
     * @param int $code the Exception code.
     * @param \Throwable|null $previous the previous exception used for the exception chaining.
     */
    public function __construct($route, $code = 0, $previous = null)
    {
        parent::__construct(sprintf('Unknown command "%s".', $route), $code, $previous);
    }
}