<?php
namespace core\widgets;
use core\base\Widget;
use core\base\BaseObject;
use core\helpers\Html;
use core\helpers\Json;
class ActiveForm extends Widget {
    public $action           = '';
    public $method           = 'post';
    public $options          = [];
    public $attributes       = [];
    public $fieldConfig      = [];
    public $fieldClass       = 'core\widgets\ActiveField';
    public $errorCssClass    = 'has-error';
    public $successCssClass  = 'has-success';
    public $validateOnChange = true;
    public $validateOnBlur   = true;
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
        $this->registerClientScript();
        return $html;
    }
    public function registerClientScript() {
        $id         = $this->options['id'];
        $attributes = Json::encode($this->attributes);
        $options    = Json::encode($this->getClientOptions());
        $view       = $this->getView();
        ActiveFormAsset::register($view);
        $view->registerJs("framework.activeForm.init('#$id', $attributes, $options);");
    }
    public function getClientOptions() {
        $options = [
            'errorCssClass'   => $this->errorCssClass,
            'successCssClass' => $this->successCssClass,
        ];
        return array_diff_assoc($options, [
            'errorCssClass'   => 'has-error',
            'successCssClass' => 'has-success',
        ]);
    }
    /**
     * @return ActiveField
     */
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