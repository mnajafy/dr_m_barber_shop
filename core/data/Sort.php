<?php
namespace core\data;
use Framework;
use core\helpers\Html;
use core\base\BaseObject;
class Sort extends BaseObject {
    public $sortParam = 'sort';
    public $defaultOrder;
    public $separator = ',';
    private $_orders;
    public function getOrders() {
        if ($this->_orders === null) {
            $this->_orders = [];
            $sortParam     = Framework::$app->getRequest()->get($this->sortParam);
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
    public function link($attribute, $options = []) {
        $url   = $this->createUrl($attribute);
        $label = $options['label'];
        unset($options['label']);
        return Html::a($label, $url, $options);
    }
    public function createUrl($attribute) {
        if (($params = $this->params) === null) {
            $params = Framework::$app->getRequest()->get();
        }
        $params[$this->sortParam] = $this->createSortParam($attribute);
        $params[0]                = Framework::$app->controller->getRoute();
        $urlManager               = Framework::$app->getUrlManager();
        return $urlManager->createUrl($params);
    }
    public function createSortParam($attribute) {
        $directions = $this->getOrders();

        $direction = SORT_ASC;
        if (isset($directions[$attribute])) {
            $direction = $directions[$attribute] === SORT_DESC ? SORT_ASC : SORT_DESC;
        }

        $orders = [$attribute => $direction];

        $sorts = [];
        foreach ($orders as $attribute => $direction) {
            $sorts[] = $direction === SORT_DESC ? '-' . $attribute : $attribute;
        }
        return implode($this->separator, $sorts);
    }
}