<?php

namespace mgine\base;

class EmptyRouteException extends InvalidRouteException
{
    public function getName()
    {
        return 'Empty Route';
    }
}
