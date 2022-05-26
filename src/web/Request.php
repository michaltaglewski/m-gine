<?php

namespace mgine\web;

use mgine\base\Component;
use mgine\helpers\ArrayHelper;

/**
 * @property array $get
 * @property array $post
 */
class Request extends Component
{
    public array $get;

    public array $post;

    public string $method;

    public HttpHeaders $headers;

    public bool $csrfValidation = true;

    public bool $csrfCookieMethod = false;

    public string $csrfParamName = '_csrf';

    public string $csrfMetaParamAttr = 'csrf-param';

    public string $csrfMetaTokenAttr = 'csrf-token';

    public function __construct(HttpHeaders $headers)
    {
        unset($_GET['_url']);

//        $this->headers = new HttpHeaders();
        $this->headers = $headers;
        $this->method = strtolower($_SERVER['REQUEST_METHOD']);

        $this->get = $_GET;
        $this->post = $_POST;

        $this->generateCsrfToken();
    }

    public function isGet() :bool
    {
        return $this->method === 'get';
    }

    public function isPost() :bool
    {
        return $this->method === 'post';
    }

    public function get(string $name, mixed $defaultValue = null) :?string
    {
        return isset($this->get[$name]) ? $this->get[$name] : $defaultValue;
    }

    public function post(string $name, mixed $defaultValue = null) :?string
    {
        return isset($this->post[$name]) ? $this->post[$name] : $defaultValue;
    }

    public function updateGetParams(array $params)
    {
        ArrayHelper::filterNumericKeys($params);

        $this->get = $_GET = array_merge($params, $_GET);
    }

    /**
     * @return array
     * @throws NotFoundHttpException
     */
    public function resolve() :array
    {
        if(($result = \App::$get->urlManager->parseRequest($this)) !== false){
            return $result;
        }

        throw new NotFoundHttpException('Page Not Found');
    }

    public function getQueryString() :?string
    {
        $path = $_SERVER['REQUEST_URI'] ?? null;

        $pos = strpos($path, '?');

        if($pos !== false){
            return substr($path, $pos);
        }
    }

    public function getPath() :?string
    {
        $path = $_SERVER['REQUEST_URI'] ?? '/';

        $pos = strpos($path, '?');

        if($pos === false){
            return $path;
        }

        return substr($path, 0, $pos);
    }

    public function getMethod() :string
    {
        return $this->method;
    }

    public function generateCsrfToken(): void
    {
        $headerToken = $this->headers->getHeaderCsrfToken();
        $postToken = $this->getPostCsrfToken();

        if($headerToken === null && $postToken === null){
            $this->_generateCsrfToken();
        }
    }

    public function JWTAuth(): \stdClass
    {
        $state = $this->get('state');
        $jwt = new JWT($this->headers, $state);

        if(!$jwt->auth()){
            throw new UnauthorizedHttpException('Unauthorized');
        }

        return $jwt->payload;
    }

    public function csrfValidate(): bool
    {
        if($this->csrfValidation && $this->isPost()){

            $trueToken = $this->getTrueCsrfToken();
            $headerToken = $this->headers->getHeaderCsrfToken();
//            $headerToken = $this->getHeaderCsrfToken();

            if($trueToken === $headerToken){
                return true;
            }

            throw new BadRequestHttpException('Unable to verify your data submission');
        }

        return false;
    }

    public function getPostCsrfToken(): ?string
    {
        return $this->post[$this->csrfParamName] ?? null;
    }

    public function getTrueCsrfToken(): ?string
    {
        if($this->csrfCookieMethod){
            $token = null;
        } else {
            $token = \App::$get->session->getValue($this->csrfParamName);
        }

        return $token;
    }

    private function _generateCsrfToken(): void
    {
        $token = \App::$get->security->generateRandomString();

        if($this->csrfCookieMethod){

        } else {
            \App::$get->session->setValue($this->csrfParamName, $token);
        }
    }

}