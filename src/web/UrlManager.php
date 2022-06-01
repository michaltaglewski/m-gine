<?php

namespace mgine\web;

/**
 * @property array $config
 * @property array $rules
 * @property Request $request
 */
class UrlManager extends \mgine\base\Component
{
//    public array $config = [];

    public array $rules = [];

    public string $defaultRoute = 'index/index';

    public Request $request;

    public function init()
    {
        $this->createRules();
    }

    private function createRules() :void
    {
//        $this->get('/', $this->defaultRoute);
        $this->rules[BASE_URL] = $this->defaultRoute;

        if(!empty($this->config['rules'])){
            foreach ($this->config['rules'] as $rule => $route){
                $this->rules[$rule] = $route;
            }
        }
    }

    public function get(string $path, string $route)
    {
//        $path = htmlspecialchars($path, ENT_QUOTES, 'UTF-8');
        $this->rules['get'][$path] = $route;
    }

    /**
     * @param Request $request
     * @return false|array
     */
    public function parseRequest(Request $request) :false|array
    {
        $this->request = $request;

        $path = $this->request->getPath();

        $ruleData = $this->findRule($path);

        if(isset($ruleData['route'])){
            $this->saveGetParams($ruleData['params']);

            return [
                $ruleData['route'],
                $this->request->get
            ];
        }

        return false;
    }

    /**
     * @param string $rule
     * @param string $path
     * @return array
     */
    public function matchRule(string $rule, string $path) :array
    {
        $pattern = $this->translateToRegexPattern($rule); // htmlspecialchars($pattern, ENT_QUOTES, 'UTF-8');

        preg_match_all($pattern, $path, $matches, PREG_SET_ORDER);

        return $matches;
    }

    /**
     * @param string $path
     * @return array
     */
    private function findRule(string $path) :array
    {
        //$method = $this->request->getMethod();

        foreach ($this->rules as $rule => $route){
            $result = $this->matchRule($rule, $path);

            if(isset($result[0])){
                return [
                    'route' => $route,
                    'params' => $result[0]
                ];
            }
        }

        return [];
    }

    /**
     * @param string $rule
     * @return string
     */
    private function translateToRegexPattern(string $rule) :string
    {
        preg_match_all("[<(([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*)(:|)(.*?))>]", $rule, $matches, PREG_SET_ORDER);

        foreach ($matches as $match){
            $pathRule = $match[1];
            $attrName = $match[2];
            $ruleRegex = $match[4];

            $ruleRegex = !empty($ruleRegex) ? $ruleRegex : '\w+';

            $rule = str_replace("<$pathRule>", "(?P<$attrName>$ruleRegex)", $rule); // (?P<name>\w+)
        }

        $pattern = "|^$rule?$|";

        return $pattern;
    }

    /**
     * @param array $params
     * @return void
     */
    private function saveGetParams(array $params) :void
    {
        $this->request?->updateGetParams($params);
    }
}