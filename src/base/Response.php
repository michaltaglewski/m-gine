<?php

namespace mgine\base;

/**
 * Response
 *
 * @author Michal Taglewski <mtaglewski.dev@gmail.com>
 */
class Response extends Component
{
    public const FORMAT_TEXT_PLAIN = 'text';

    public string $format = self::FORMAT_TEXT_PLAIN;

    public string|array $content;

    public int $statusCode = 0;

    /**
     * @return void
     */
    public function send(): void
    {
        echo $this->content;

        exit($this->statusCode);
    }

    /**
     * Sends and handle Exception error to the client
     * @param \Throwable $ex
     * @return void
     */
    public function sendError(\Throwable $ex)
    {
        defined('DEBUG_MODE') or define('DEBUG_MODE', false);

        if(DEBUG_MODE){
            throw $ex;
        }
    }

}