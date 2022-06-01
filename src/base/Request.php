<?php

namespace mgine\base;

/**
 * Request
 *
 * @author Michal Tglewski <mtaglewski.dev@gmail.com>
 */
abstract class Request extends Component
{
    abstract public function resolve(): array;
}