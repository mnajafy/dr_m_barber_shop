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
    public function parseRequest($request) {
        $route = null;
        foreach ($this->rules as $key => $value) {
            if ($this->match($request->pathInfo, $key)) {
                $route = $value;
                break;
            }
        }
        if ($route === null) {
            throw new Exception('request page not found!');
        }
        return [$route, $this->_params];
    }
    /**
     * @param string $path
     * @param string $key
     * @return bool
     */
    public function match($path, $key) {
        $name    = preg_replace('#{([\w]+)}#', '([^/]+)', $key);
        $regex   = "#^$name$#i";
        $matches = [];
        if (!preg_match($regex, $path, $matches)) {
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