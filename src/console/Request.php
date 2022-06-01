<?php

namespace mgine\console;

/**
 * Request
 *
 * @author Michal Tglewski <mtaglewski.dev@gmail.com>
 */
class Request extends \mgine\base\Request
{
    /**
     * @var array
     */
    public array $routeAliases = [];

    /**
     * @var array
     */
    private array $_argv = [];

    /**
     * @param $config
     */
    public function __construct($config = [])
    {
        parent::__construct($config);

        $this->setArguments();
    }

    /**
     * @return string|null
     */
    public function getRoute(): ?string
    {
        return $this->_argv[0] ?? null;
    }

    /**
     * @return array
     */
    public function getParams(): array
    {
        return array_slice($this->_argv, 1);
    }

    /**
     * @return array
     */
    public function getArguments(): array
    {
        return $this->_argv;
    }

    /**
     * @return array
     */
    public function resolve(): array
    {
        $route = $this->getRoute();

        if(isset($this->routeAliases[$route])){
            $route = $this->routeAliases[$route];
        }

        return [$route, $this->getParams()];
    }

    /**
     * @return void
     */
    private function setArguments(): void
    {
        $this->_argv = $_SERVER['argv'];

        array_shift($this->_argv);
    }
}