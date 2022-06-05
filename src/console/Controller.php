<?php

namespace mgine\console;

/**
 * Console Controller
 *
 * @author Michal Taglewski <mtaglewski.dev@gmail.com>
 */
class Controller extends \mgine\base\Controller
{
    /**
     * @var array
     */
    private array $cliTextColors = [
        'black' => '30',
        'red' => '31',
        'green' => '32',
        'yellow' => '33',
        'blue' => '34',
        'magenta' => '35',
        'cyan' => '36',
        'light-gray' => '37',
        'dark-gray' => '90',
        'light-red' => '91',
        'light-green' => '92',
        'light-yellow' => '93',
        'light-blue' => '94',
        'light-magenta' => '95',
        'light-cyan' => '96',
        'white' => '97',
    ];

    /**
     * @param string $color
     * @return void
     */
    protected function setTextColor(string $color): void
    {
        $c = $this->cliTextColors[$color] ?? null;

        print "\e[{$c}m";
    }

    /**
     * @return void
     */
    protected function resetTextColor(): void
    {
        print "\e[0m";
    }

    /**
     * @param string $string
     * @param string $color
     * @return void
     */
    protected function addTextColor(string &$string, string $color): void
    {
        $c = $this->cliTextColors[$color] ?? null;

        $string = "\e[{$c}m$string\e[0m";
    }

    /**
     * @param string $line
     * @param string|null $color
     * @return void
     */
    protected function printLine(string $line, string $color = null): void
    {
        if($color !== null){
            $this->addTextColor($line, $color);
        }

        print $line . PHP_EOL;
    }
}