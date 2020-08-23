<?php
namespace core\web;
use Framework;
use core\base\BaseObject;
/**
 * UrlManager
 * @property UrlRule[] $rules
 */
class UrlManager extends BaseObject {
    /**
     * @var UrlRule[]
     */
    private $_rules = [];
    /**
     * @return UrlRule[]
     */
    public function getRules() {
        return $this->_rules;
    }
    /**
     * @param UrlRule[] $rules
     */
    public function setRules($rules) {
        $this->_rules = $rules;
    }
    /**
     * @param array $rules
     */
    public function addRules($rules, $append = true) {
        $builtRules = $this->buildRules($rules);
        $this->rules = $append ? array_merge($this->rules, $builtRules) : array_merge($builtRules, $this->rules);
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
        foreach ($this->getRules() as $rule) {
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
     * @param string $pathInfo
     * @return array
     */
    public function parseRequest($pathInfo) {
        foreach ($this->getRules() as $index => $rule) {
            $result = $rule->parseRequest($pathInfo);
            if ($result !== false) {
                return $result;
            }
        }
        return [$pathInfo, []];
    }
}