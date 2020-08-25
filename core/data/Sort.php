<?php
namespace core\data;
use Framework;
use core\base\BaseObject;
class Sort extends BaseObject {
    public $sortParam = 'sort';
    public $defaultOrder;
    public $separator = ',';
    private $_orders;
    public function getOrders() {
        if ($this->_orders === null) {
            $this->_orders = [];
            $sortParam = Framework::$app->getRequest()->get($this->sortParam);
            foreach ($this->parseSortParam($sortParam) as $attribute) {
                $descending = false;
                if (strncmp($attribute, '-', 1) === 0) {
                    $descending = true;
                    $attribute  = substr($attribute, 1);
                }
                $this->_orders[$attribute] = $descending ? SORT_DESC : SORT_ASC;
            }
            if (empty($this->_orders) && is_array($this->defaultOrder)) {
                $this->_orders = $this->defaultOrder;
            }
        }
        return $this->_orders;
    }
    public function parseSortParam($param) {
        return is_scalar($param) ? explode($this->separator, $param) : [];
    }
}