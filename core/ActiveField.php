<?php
namespace Core;
class ActiveField extends BaseObject {
    public $model;
    public $attribute;
    public $form;
    public $options      = ['class' => 'form-group'];
    public $template     = "{label}\n{input}";
    public $labelOptions = [];
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
        $content = strtr($this->template, $this->parts);
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
        $options = array_merge($this->labelOptions, $options);
        if ($label !== null) {
            $options['label'] = $label;
        }
        $this->parts['{label}'] = Html::activeLabel($this->model, $this->attribute, $options);
        return $this;
    }
    public function textInput($options = []) {
        $options = array_merge($this->inputOptions, $options);
        $this->parts['{input}'] = Html::activeTextInput($this->model, $this->attribute, $options);
        return $this;
    }
}