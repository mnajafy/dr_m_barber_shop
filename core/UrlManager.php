<?php
namespace Core;
use Exception;
/**
 * UrlManager
 * @property array $rules
 */
class UrlManager extends BaseObject {
    /**
     * @var array
     */
    private $_params;
    /**
     * @param Request $request
     */
    public function resolve($request) {
        $route = null;
        $url   = ($request->get('r') === null ? '/' : trim($request->get('r'), '/'));
        foreach ($this->rules as $key => $value) {
            if ($this->match($url, $key)) {
                $route = $value;
                break;
            }
        }
        if ($route === null) {
            throw new Exception('request page not found!');
        }
        $params = $request->merge($this->_params);
        return [$route, $params];
    }
    /**
     * @param string $url
     * @param string $key
     * @return bool
     */
    public function match($url, $key) {
        $name    = preg_replace('#{([\w]+)}#', '([^/]+)', $key);
        $regex   = "#^$name$#i";
        $matches = [];
        if (!preg_match($regex, $url, $matches)) {
            return false;
        }

        $keys  = [];
        $items = [];
        preg_match_all('#{([\w]+)}#', $key, $items);
        if ($items[1]) {
            $keys = $items[1];
        }

        $params = [];
        array_shift($matches);
        foreach ($matches as $index => $value) {
            $params[$keys[$index]] = $value;
        }
        $this->_params = $params;
        return true;
    }
}