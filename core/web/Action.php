<?php
namespace core\web;
use ReflectionMethod;
use Exception;
use core\base\BaseObject;
/**
 * Action
 */
class Action extends BaseObject {
    public $id;
    public $controller;
    public $actionMethod;
    public function run($params) {
        $method = new ReflectionMethod($this->controller, $this->actionMethod);
        $args   = [];
        foreach ($method->getParameters() as $param) {
            /* @var $param \ReflectionParameter */
            if (isset($params[$param->name])) {
                $args[$param->name] = $params[$param->name];
            }
            else if ($param->isOptional()) {
                $args[$param->name] = $param->getDefaultValue();
            }
            else {
                throw new Exception("Parameter {<b>$param->name</b>} Is Missing");
            }
        }
        return call_user_func_array([$this->controller, $this->actionMethod], $args);
    }
}