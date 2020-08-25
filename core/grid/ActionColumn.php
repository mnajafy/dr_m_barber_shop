<?php
namespace core\grid;
use core\helpers\Url;
use core\helpers\Html;
class ActionColumn extends Column {
    public $controller;
    public $urlCreator;
    public $template       = '{view} {update} {delete}';
    public $headerOptions  = ['class' => 'action-column'];
    public $buttons        = [];
    public $visibleButtons = [];
    public $buttonOptions  = [];
    public function init() {
        parent::init();
        $this->initDefaultButtons();
    }
    public function initDefaultButtons() {
        $this->initDefaultButton('view', 'eye-open');
        $this->initDefaultButton('update', 'pencil');
        $this->initDefaultButton('delete', 'trash', [
            'data-confirm' => 'Are you sure you want to delete this item?',
            'data-method'  => 'post',
        ]);
    }
    public function initDefaultButton($name, $iconName, $additionalOptions = []) {
        if (!isset($this->buttons[$name]) && strpos($this->template, '{' . $name . '}') !== false) {
            $this->buttons[$name] = function ($url, $model, $key) use ($name, $iconName, $additionalOptions) {
                switch ($name) {
                    case 'view':
                        $title = 'View';
                        break;
                    case 'update':
                        $title = 'Update';
                        break;
                    case 'delete':
                        $title = 'Delete';
                        break;
                    default:
                        $title = ucfirst($name);
                }
                $options = array_merge(['title' => $title], $additionalOptions, $this->buttonOptions);
                return Html::a($title, $url, $options);
            };
        }
    }
    public function createUrl($action, $model, $key, $index) {
        if (is_callable($this->urlCreator)) {
            return call_user_func($this->urlCreator, $action, $model, $key, $index, $this);
        }
        $params    = is_array($key) ? $key : ['id' => (string) $key];
        $params[0] = $this->controller ? $this->controller . '/' . $action : $action;
        return Url::toRoute($params);
    }
    public function renderDataCellContent($model, $key, $index) {
        return preg_replace_callback('/\\{([\w\-\/]+)\\}/', function ($matches) use ($model, $key, $index) {
            $name = $matches[1];

            if (isset($this->visibleButtons[$name])) {
                $isVisible = $this->visibleButtons[$name] instanceof \Closure ? call_user_func($this->visibleButtons[$name], $model, $key, $index) : $this->visibleButtons[$name];
            }
            else {
                $isVisible = true;
            }

            if ($isVisible && isset($this->buttons[$name])) {
                $url = $this->createUrl($name, $model, $key, $index);
                return call_user_func($this->buttons[$name], $url, $model, $key);
            }
            return '';
        }, $this->template);
    }
}