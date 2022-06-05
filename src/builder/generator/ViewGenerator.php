<?php

namespace mgine\builder\generator;

/**
 * ViewGenerator
 *
 * @author Michal Taglewski <mtaglewski.dev@gmail.com>
 */
class ViewGenerator extends \mgine\builder\Generator
{
    public string $directory = 'views';

    public string $name = 'index';

    public function getViewProperties(): array
    {
        return $this->customParams['viewProperties'] ?? [];
    }

}