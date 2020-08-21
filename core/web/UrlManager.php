<?php
namespace core\web;
use Framework;
use core\base\BaseObject;
/**
 * UrlManager
 * @property array $rules
 */
class UrlManager extends BaseObject {
    /**
     * @var array
     */
    private $_rules  = [];
    /**
     * @return array
     */
    public function getRules() {
        return $this->_rules;
    }
    /**
     * @param array $rules
     */
    public function setRules($rules) {
        $this->_rules = $rules;
    }
    /**
     * 
     */
    public function init() {
        if (empty($this->_rules)) {
            return;
        }
        $this->_rules = $this->buildRules($this->_rules);
    }
    /**
     * 
     */
    public function buildRules($ruleDeclarations) {
        $builtRules = [];
        foreach ($ruleDeclarations as $pattern => $route) {
            $config       = ['class' => UrlRule::class, 'pattern' => $pattern, 'route' => $route];
            $builtRules[] = BaseObject::createObject($config);
        }
        return $builtRules;
    }
    /**
     * @param array $params
     * @return string
     */
    public function createUrl($params = []) {

        $anchor = isset($params['#']) ? '#' . $params['#'] : '';
        unset($params['#']);

        $route = trim($params[0], '/');
        unset($params[0]);

        $url = false;
        foreach ($this->rules as $index => $rule) {
            /* @var $rule UrlRule */
            $url = $rule->createUrl($route, $params);
            if ($url !== false) {
                break;
            }
        }

        $baseUrl = Framework::$app->getRequest()->getBaseUrl();
        if ($url !== false) {
            $url = ltrim($url, '/');
            return "$baseUrl/{$url}{$anchor}";
        }

        if (!empty($params) && ($query = http_build_query($params)) !== '') {
            $route .= '?' . $query;
        }

        $route2 = ltrim($route, '/');
        return "$baseUrl/{$route2}{$anchor}";
    }
    /**
     * @param Request $request
     * @return array
     */
    public function parseRequest($request) {
        foreach ($this->rules as $index => $rule) {
            $result = $rule->parseRequest($this, $request);
            if ($result !== false) {
                return $result;
            }
        }
        $pathInfo = $request->getPathInfo();
        return [$pathInfo, []];
    }
}