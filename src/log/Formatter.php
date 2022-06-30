<?php

declare(strict_types=1);

namespace mgine\log;

/**
 * Formatter
 *
 * @author Michal Taglewski <mtaglewski.dev@gmail.com>
 */
class Formatter extends \mgine\base\Formatter
{
    public string $type;

    public string $dateFormat = "D, d M y H:i:s O";

    protected string $format = '[%date%] [%type%] %message%';

    public function __construct(string $format = null)
    {
        parent::__construct();

        if($format !== null){
            $this->format = $format;
        }
    }

    public function setDateFormat(string $dateFormat)
    {
        $this->dateFormat = $dateFormat;
    }

    public function setType(string $type)
    {
        $this->type = $type;
    }

    public function getMessage(string $message)
    {
        $params = [
            '%type%'=> $this->type,
            '%date%' => date($this->dateFormat),
            '%message%' => $message
        ];

        return strtr($this->format, $params) . PHP_EOL;
    }
}