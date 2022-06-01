<?php

namespace mgine\web;

use mgine\base\Component;
use mgine\helpers\ArrayHelper;

/**
 * HttpHeaders
 *
 * @author Michal Tglewski <mtaglewski.dev@gmail.com>
 */
class HttpHeaders extends Component
{
    public const AUTH_BASIC = 'Basic';

    public const AUTH_BEARER = 'Bearer';

    public string $authorizationHeaderName = 'Authorization';

    public string $csrfHeaderName = 'X-Csrf-Token'; // 'X-CSRF-TOKEN' @TODO

    public array|false $headers;

    public array $responseHeaders = [];

    public function __construct()
    {
        $this->headers = getallheaders();
    }

    public function getHeaderCsrfToken(): ?string
    {
        return $this->headers[$this->csrfHeaderName] ?? null;
    }

    public function getAuthorization($scheme = 'Basic')
    {
        if(!isset($this->headers[$this->authorizationHeaderName])){
            return null;
        }

        $auth = $this->headers[$this->authorizationHeaderName];

        if($scheme === self::AUTH_BEARER){
            preg_match("/^Bearer\\s+(.*?)$/", $auth, $matches);

            if(isset($matches[1])){
                return $matches[1];
            }
        }

        return $auth;
    }

    public function setResponseHeader(string $name, string|array $value)
    {
        $this->responseHeaders[$name] = $value;
    }

    public function send()
    {
        foreach (ArrayHelper::toHttpHeaderFormat($this->responseHeaders) as $header){
            header($header);
        }
    }
}