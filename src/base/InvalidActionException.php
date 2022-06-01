<?php

namespace mgine\base;

class InvalidActionException extends \Exception
{
    public function __construct($actionId, $controllerId, $code = 0, $previous = null)
    {
        parent::__construct(sprintf('Could not find action "%s" at: "%s"', $actionId, $controllerId), $code, $previous);
    }
}