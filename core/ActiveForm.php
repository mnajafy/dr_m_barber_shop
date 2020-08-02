<?php
namespace Core;
class ActiveForm extends Widget {
    public $action      = '';
    public $method      = 'post';
    public $options     = [];
    public $fieldConfig = [];
    public $fieldClass  = 'Core\ActiveField';
    public function init() {
        parent::init();
        if (!isset($this->options['id'])) {
            $this->options['id'] = $this->getId();
        }
        ob_start();
        ob_implicit_flush(false);
    }
    public function run() {
        $content = ob_get_clean();
        $html    = Html::beginForm($this->action, $this->method, $this->options);
        $html    .= $content;
        $html    .= Html::endForm();
        return $html;
    }
    public function field($model, $attribute, $options = []) {
        $config = $this->fieldConfig;
        if (!isset($config['class'])) {
            $config['class'] = $this->fieldClass;
        }
        return BaseObject::createObject(array_merge($config, $options, [
                    'model'     => $model,
                    'attribute' => $attribute,
                    'form'      => $this,
        ]));
    }
}