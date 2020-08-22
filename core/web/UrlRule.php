<?php
namespace core\web;
use Exception;
use core\base\BaseObject;
class UrlRule extends BaseObject {
    public $pattern;
    public $route;
    /**
     * @var array list of placeholders for matching parameters names. Used in [[parseRequest()]], [[createUrl()]].
     * On the rule initialization, the [[pattern]] parameters names will be replaced with placeholders.
     * This array contains relations between the original parameters names and their placeholders.
     * The array keys are the placeholders and the values are the original names.
     *
     * @see parseRequest()
     * @see createUrl()
     */
    public $placeholders = [];
    /**
     * @var string the template for generating a new URL. This is derived from [[pattern]] and is used in generating URL.
     */
    public $template;
    /**
     * @var array list of regex for matching parameters. This is used in generating URL.
     */
    public $paramRules   = [];
    /**
     * @var array list of parameters used in the route.
     */
    public $routeParams  = [];
    /**
     * @var string the regex for matching the route part. This is used in generating URL.
     */
    public $routeRule;
    public function init() {
        if ($this->pattern === null) {
            throw new Exception('UrlRule::pattern must be set.');
        }
        if ($this->route === null) {
            throw new Exception('UrlRule::route must be set.');
        }
        $this->preparePattern();
    }
    public function preparePattern() {
        $this->pattern = $this->trimSlashes($this->pattern);
        $this->route   = trim($this->route, '/');
        if ($this->pattern === '') {
            $this->template = '';
            $this->pattern  = '#^$#u';
            return;
        }
        else {
            $this->pattern = '/' . $this->pattern . '/';
        }
        $matches = [];
        if (strpos($this->route, '<') !== false && preg_match_all('/<([\w._-]+)>/', $this->route, $matches)) {
            foreach ($matches[1] as $name) {
                $this->routeParams[$name] = "<$name>";
            }
        }
        $this->translatePattern(true);
    }
    public function translatePattern() {
        $tr      = ['.' => '\\.', '*' => '\\*', '$' => '\\$', '[' => '\\[', ']' => '\\]', '(' => '\\(', ')' => '\\)'];
        $tr2     = [];
        $matches = [];
        if (preg_match_all('/<([\w._-]+):?([^>]+)?>/', $this->pattern, $matches, PREG_OFFSET_CAPTURE | PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $name                             = $match[1][0];
                $pattern                          = isset($match[2][0]) ? $match[2][0] : '[^\/]+';
                $placeholder                      = 'a' . hash('crc32b', $name); // placeholder must begin with a letter
                $this->placeholders[$placeholder] = $name;
                $tr["<$name>"]                    = "(?P<$placeholder>$pattern)";
                if (isset($this->routeParams[$name])) {
                    $tr2["<$name>"] = $tr["<$name>"];
                }
                else {
                    $this->paramRules[$name] = $pattern === '[^\/]+' ? '' : "#^$pattern$#u";
                }
            }
        }
        $this->template = preg_replace('/<([\w._-]+):?([^>]+)?>/', '<$1>', $this->pattern);
        $this->pattern  = '#^' . trim(strtr($this->template, $tr), '/') . '$#u';
        if (!empty($this->routeParams)) {
            $this->routeRule = '#^' . strtr($this->route, $tr2) . '$#u';
        }
    }
    /**
     * @param string $route
     * @param array $params
     */
    public function createUrl($route = '', $params = []) {
        $tr = [];
        if ($route !== $this->route) {
            $matches = [];
            if ($this->routeRule === null || !preg_match($this->routeRule, $route, $matches)) {
                return false;
            }
            $names = $this->substitutePlaceholderNames($matches);
            foreach ($this->routeParams as $name => $token) {
                $tr[$token] = $names[$name];
            }
        }
        foreach ($this->paramRules as $name => $rule) {
            if (!isset($params[$name])) {
                return false;
            }
            if ($rule === '' || preg_match($rule, $params[$name])) {
                $tr["<$name>"] = $params[$name];
                unset($params[$name]);
            }
        }
        return $this->trimSlashes(strtr($this->template, $tr)) . (!empty($params) && ($query = http_build_query($params)) !== '' ? '?' . $query : '');
    }
    /**
     * @param string $pathInfo
     * @return array|false
     */
    public function parseRequest($pathInfo) {
        $matches = [];
        if (!preg_match($this->pattern, $pathInfo, $matches)) {
            return false;
        }
        $names  = $this->substitutePlaceholderNames($matches);
        $params = [];
        $tr     = [];
        foreach ($names as $name => $value) {
            if (isset($this->routeParams[$name])) {
                $tr[$this->routeParams[$name]] = $value;
                unset($params[$name]);
            }
            elseif (isset($this->paramRules[$name])) {
                $params[$name] = $value;
            }
        }
        $route = ($this->routeRule === null ? $this->route : strtr($this->route, $tr));
        return [$route, $params];
    }
    public function substitutePlaceholderNames($matches) {
        foreach ($this->placeholders as $placeholder => $name) {
            if (isset($matches[$placeholder])) {
                $matches[$name] = $matches[$placeholder];
                unset($matches[$placeholder]);
            }
        }
        return $matches;
    }
    public function trimSlashes($string) {
        if (strncmp($string, '//', 2) === 0) {
            return '//' . trim($string, '/');
        }
        return trim($string, '/');
    }
}