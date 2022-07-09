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
    /**
     * @var string
     */
    public string $type;

    /**
     * @var string
     */
    public string $dateFormat = "D, d M y H:i:s O";

    /**
     * @var string
     */
    protected string $format = '[%date%] [%type%] %message%';

    /**
     * @param string|null $format
     * @throws \mgine\base\InvalidConfigException
     */
    public function __construct(string $format = null)
    {
        parent::__construct();

        if($format !== null){
            $this->format = $format;
        }
    }

    /**
     * @param string $dateFormat
     * @return void
     */
    public function setDateFormat(string $dateFormat): void
    {
        $this->dateFormat = $dateFormat;
    }

    /**
     * @param string $type
     * @return void
     */
    public function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * @param string $message
     * @return string
     */
    public function getMessage(string $message): string
    {
        $params = [
            '%type%'=> $this->type,
            '%date%' => date($this->dateFormat),
            '%message%' => $message
        ];

        return strtr($this->format, $params) . PHP_EOL;
    }
}