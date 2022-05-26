<?php

namespace mgine\web;

use mgine\base\Component;
use mgine\base\HeadersAlreadySentException;

class Response extends Component
{
    public const FORMAT_TEXT_PLAIN = 'text';

    public const FORMAT_HTML = 'html';

    public const FORMAT_JSON = 'json';

    public string $format = self::FORMAT_HTML;

    public string $charset = 'utf-8';

    public string|array $content;

    public HttpHeaders $headers;

    public int $statusCode = 0;

    public function __construct(HttpHeaders $headers)
    {
        $this->headers = $headers;
    }

    public function asJson()
    {
        $this->format = self::FORMAT_JSON;
    }

    public function asTextPlain()
    {
        $this->format = self::FORMAT_TEXT_PLAIN;
    }

    public function send()
    {
        if($this->format === self::FORMAT_HTML && is_array($this->content)){
            throw new \Exception('Response text/html format must not be array');
        } elseif($this->format === self::FORMAT_JSON && !is_array($this->content)){
            throw new \Exception('Response application/json format must be array');
        }

        $this->applyResponseFormat();
        $this->sendHeaders();

        if($this->format === self::FORMAT_JSON){
            echo json_encode($this->content);
        } else {
            echo $this->content;
        }

        exit($this->statusCode);
    }

    /**
     * Sends and handle HttpException error to the client
     * @param HttpException $ex
     * @return void
     */
    public function sendError(HttpException $ex)
    {
        http_response_code($ex->statusCode);

        if(DEBUG_MODE){
            throw $ex;
        }

        echo $ex->getMessage();
    }

    public function applyResponseFormat()
    {
        switch ($this->format){
            case self::FORMAT_TEXT_PLAIN: $this->setHeader('Content-Type', ['text/plain', 'charset' => $this->charset]); break;
            case self::FORMAT_JSON: $this->setHeader('Content-Type', ['application/json', 'charset' => $this->charset]); break;
            default: $this->setHeader('Content-Type', ['text/html', 'charset' => $this->charset]); break;
        }
    }

    public function setHeader(string $name, string|array $value)
    {
        foreach (headers_list() as $item){
            if(stripos($item, $name) !== false){
                return false;
            }
        }

        $this->headers->setResponseHeader($name, $value);
    }

    protected function sendHeaders()
    {
        if (headers_sent($file, $line)) {
            throw new HeadersAlreadySentException($file, $line);
        }

        $this->headers->send();
    }


}