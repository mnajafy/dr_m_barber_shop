<?php
namespace core\widgets;
use core\helpers\Html;
use core\base\BaseObject;
class ActiveField extends BaseObject {
    /**
     * @var \core\base\Model
     */
    public $model;
    public $attribute;
    public $form;
    public $options      = ['class' => ['form-group']];
    public $template     = "{label}\n{input}\n{error}";
    public $labelOptions = ['class' => 'control-label'];
    public $errorOptions = ['class' => 'form-error'];
    public $inputOptions = ['class' => 'form-control'];
    public $parts        = [];
    public function __toString() {
        return $this->render();
    }
    public function render() {
        if (!isset($this->parts['{input}'])) {
            $this->textInput();
        }
        if (!isset($this->parts['{label}'])) {
            $this->label();
        }
        if (!isset($this->parts['{error}'])) {
            $this->error();
        }
        $content  = strtr($this->template, $this->parts);
        $hasError = $this->model->hasErrors($this->attribute);
        if ($hasError) {
            if (is_array($this->options['class'])) {
                $this->options['class'][] = 'has-error';
            }
            else {
                $this->options['class'] .= ' has-error';
            }
        }
        return $this->begin() . "\n" . $content . "\n" . $this->end();
    }
    public function begin() {
        return Html::beginTag('div', $this->options);
    }
    public function end() {
        return Html::endTag('div');
    }
    public function label($label = null, $options = []) {
        if ($label === false) {
            $this->parts['{label}'] = '';
            return $this;
        }
        $config = array_merge($this->labelOptions, $options);
        if ($label !== null) {
            $config['label'] = $label;
        }
        $this->parts['{label}'] = Html::activeLabel($this->model, $this->attribute, $config);
        return $this;
    }
    public function error($options = []) {
        if ($options === false) {
            $this->parts['{error}'] = '';
            return $this;
        }
        $config                 = array_merge($this->errorOptions, $options);
        $this->parts['{error}'] = Html::error($this->model, $this->attribute, $config);
        return $this;
    }
    public function textInput($options = []) {
        $config                 = array_merge($this->inputOptions, $options);
        $this->parts['{input}'] = Html::activeTextInput($this->model, $this->attribute, $config);
        return $this;
    }
}