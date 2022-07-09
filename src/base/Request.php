<?php

namespace mgine\base;

/**
 * Request
 *
 * @author Michal Taglewski <mtaglewski.dev@gmail.com>
 */
abstract class Request extends Component implements RequestInterface
{
    abstract public function resolve(): array;
}