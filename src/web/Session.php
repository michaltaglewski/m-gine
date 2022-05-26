<?php

namespace mgine\web;

use mgine\base\Component;

class Session extends Component
{
    public function __construct()
    {
        session_start();
    }

    /**
     * @param $name
     * @param $defaultValue
     * @return mixed
     */
    public function getValue($name, $defaultValue = null): mixed
    {
        return $_SESSION[$name] ?? $defaultValue;
    }

    /**
     * @param $name
     * @param $value
     * @return void
     */
    public function setValue($name, $value): void
    {
        $_SESSION[$name] = $value;
    }
}