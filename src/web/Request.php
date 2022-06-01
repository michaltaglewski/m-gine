<?php

namespace mgine\web;

use mgine\helpers\ArrayHelper;

/**
 * Request
 *
 * @author Michal Tglewski <mtaglewski.dev@gmail.com>
 */
class Request extends \mgine\base\Request
{
    /**
     * @var array
     */
    public array $get;

    /**
     * @var array
     */
    public array $post;

    /**
     * @var string
     */
    public string $method;

    /**
     * @var HttpHeaders
     */
    public HttpHeaders $headers;

    /**
     * @var bool
     */
    public bool $csrfValidation = true;

    /**
     * @var bool
     */
    public bool $csrfCookieMethod = false;

    /**
     * @var string
     */
    public string $csrfParamName = '_csrf';

    /**
     * @var string
     */
    public string $csrfMetaParamAttr = 'csrf-param';

    /**
     * @var string
     */
    public string $csrfMetaTokenAttr = 'csrf-token';

    /**
     * @param HttpHeaders $headers
     */
    public function __construct(HttpHeaders $headers)
    {
        unset($_GET['_url']);

        $this->headers = $headers;
        $this->method = strtolower($_SERVER['REQUEST_METHOD']);

        $this->get = $_GET;
        $this->post = $_POST;

        $this->generateCsrfToken();
    }

    /**
     * @return bool
     */
    public function isGet() :bool
    {
        return $this->method === 'get';
    }

    /**
     * @return bool
     */
    public function isPost() :bool
    {
        return $this->method === 'post';
    }

    /**
     * @param string $name
     * @param mixed|null $defaultValue
     * @return string|null
     */
    public function get(string $name, mixed $defaultValue = null) :?string
    {
        return isset($this->get[$name]) ? $this->get[$name] : $defaultValue;
    }

    /**
     * @param string $name
     * @param mixed|null $defaultValue
     * @return string|null
     */
    public function post(string $name, mixed $defaultValue = null) :?string
    {
        return isset($this->post[$name]) ? $this->post[$name] : $defaultValue;
    }

    /**
     * @param array $params
     * @return void
     */
    public function updateGetParams(array $params): void
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

    /**
     * @return string|null
     */
    public function getQueryString() :?string
    {
        $path = $_SERVER['REQUEST_URI'] ?? null;

        $pos = strpos($path, '?');

        if($pos !== false){
            return substr($path, $pos);
        }

        return null;
    }

    /**
     * @return string|null
     */
    public function getPath() :?string
    {
        $path = $_SERVER['REQUEST_URI'] ?? '/';

        $pos = strpos($path, '?');

        if($pos === false){
            return $path;
        }

        return substr($path, 0, $pos);
    }

    /**
     * @return string
     */
    public function getMethod() :string
    {
        return $this->method;
    }

    /**
     * @return void
     */
    public function generateCsrfToken(): void
    {
        $headerToken = $this->headers->getHeaderCsrfToken();
        $postToken = $this->getPostCsrfToken();

        if($headerToken === null && $postToken === null){
            $this->_generateCsrfToken();
        }
    }

    /**
     * @return \stdClass
     * @throws UnauthorizedHttpException
     */
    public function JWTAuth(): \stdClass
    {
        $state = $this->get('state');
        $jwt = new JWT($this->headers, $state);

        if(!$jwt->auth()){
            throw new UnauthorizedHttpException('Unauthorized');
        }

        return $jwt->payload;
    }

    /**
     * @return bool
     * @throws BadRequestHttpException
     */
    public function csrfValidate(): bool
    {
        if($this->csrfValidation && $this->isPost()){

            $trueToken = $this->getTrueCsrfToken();
            $headerToken = $this->headers->getHeaderCsrfToken();

            if($trueToken === $headerToken){
                return true;
            }

            throw new BadRequestHttpException('Unable to verify your data submission');
        }

        return false;
    }

    /**
     * @return string|null
     */
    public function getPostCsrfToken(): ?string
    {
        return $this->post[$this->csrfParamName] ?? null;
    }

    /**
     * @return string|null
     */
    public function getTrueCsrfToken(): ?string
    {
        if($this->csrfCookieMethod){
            $token = null;
        } else {
            $token = \App::$get->session->getValue($this->csrfParamName);
        }

        return $token;
    }

    /**
     * @return void
     */
    private function _generateCsrfToken(): void
    {
        $token = \App::$get->security->generateRandomString();

        if($this->csrfCookieMethod){

        } else {
            \App::$get->session->setValue($this->csrfParamName, $token);
        }
    }

}