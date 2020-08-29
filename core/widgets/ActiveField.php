<?php
namespace core\widgets;
use core\base\BaseObject;
use core\web\JsExpression;
use core\helpers\ArrayHelper;
use core\helpers\Html;
class ActiveField extends BaseObject {
    /**
     * @var \core\base\Model
     */
    public $model;
    public $attribute;
    /**
     * @var ActiveForm
     */
    public $form;
    public $template     = "{label}\n{input}\n{error}";
    public $options      = ['class' => ['form-group']];
    public $labelOptions = ['class' => 'control-label'];
    public $errorOptions = ['class' => 'form-error'];
    public $inputOptions = ['class' => 'form-control'];
    public $parts        = [];
    public $validateOnChange;
    public $validateOnBlur;
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
        $clientOptions = $this->getClientOptions();
        if (!empty($clientOptions)) {
            $this->form->attributes[] = $clientOptions;
        }
        $options          = $this->options;
        $class            = isset($options['class']) ? (array) $options['class'] : [];
        $class[]          = "field-{$this->attribute}";
        $options['class'] = implode(' ', $class);
        $tag              = ArrayHelper::remove($options, 'tag', 'div');
        return Html::beginTag($tag, $options);
    }
    public function getClientOptions() {
        if (!in_array($this->attribute, $this->model->attributes(), true)) {
            return [];
        }
        $validators = [];
        foreach ($this->model->getValidators() as $validator) {
            /* @var $validator \core\validators\Validator */
            if (!in_array($this->attribute, $validator->attributes)) {
                continue;
            }
            $js = $validator->clientValidateAttribute($this->model, $this->attribute, $this->form->getView());
            if ($js) {
                $validators[] = $js;
            }
        }
        if (empty($validators)) {
            return [];
        }
        $options                     = [];
        $options['id']               = '#' . $this->attribute;
        $options['error']            = (isset($this->errorOptions['class']) ? '.' . implode('.', preg_split('/\s+/', $this->errorOptions['class'], -1, PREG_SPLIT_NO_EMPTY)) : (isset($this->errorOptions['tag']) ? $this->errorOptions['tag'] : 'span'));
        $options['container']        = ".field-{$this->attribute}";
        $options['validateOnChange'] = $this->validateOnChange === null ? $this->form->validateOnChange : $this->validateOnChange;
        $options['validateOnBlur']   = $this->validateOnBlur === null ? $this->form->validateOnBlur : $this->validateOnBlur;
        if (!empty($validators)) {
            $options['validate'] = new JsExpression('function (attribute, value, messages) {' . implode('', $validators) . '}');
        }
        return $options;
    }
    public function end() {
        return Html::endTag('div');
    }
    public function adjustLabelFor($options) {
        if (isset($options['id']) && !isset($this->labelOptions['for'])) {
            $this->labelOptions['for'] = $options['id'];
        }
    }
    //
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
        $this->parts['{error}'] = Html::activeError($this->model, $this->attribute, $config);
        return $this;
    }
    //
    public function numberInput($options = []) {
        $config                 = array_merge($this->inputOptions, $options);
        $this->parts['{input}'] = Html::activeInput('number', $this->model, $this->attribute, $config);
        $this->adjustLabelFor($options);
        return $this;
    }
    public function textInput($options = []) {
        $config                 = array_merge($this->inputOptions, $options);
        $this->parts['{input}'] = Html::activeInput('text', $this->model, $this->attribute, $config);
        $this->adjustLabelFor($options);
        return $this;
    }
    public function hiddenInput($options = []) {
        $config                 = array_merge($this->inputOptions, $options);
        $this->parts['{input}'] = Html::activeInput('hidden', $this->model, $this->attribute, $config);
        $this->label(false);
        $this->error(false);
        return $this;
    }
    public function passwordInput($options = []) {
        $config                 = array_merge($this->inputOptions, $options);
        $this->parts['{input}'] = Html::activeInput('password', $this->model, $this->attribute, $config);
        $this->adjustLabelFor($config);
        return $this;
    }
    public function fileInput($options = []) {
        $this->parts['{input}'] = Html::activeInput('file', $this->model, $this->attribute, $options);
        $this->adjustLabelFor($config);
        return $this;
    }
    //
    public function textarea($options = []) {
        $config                 = array_merge($this->inputOptions, $options);
        $this->parts['{input}'] = Html::activeTextarea($this->model, $this->attribute, $config);
        $this->adjustLabelFor($config);
        return $this;
    }
    public function dropDownList($items = [], $options = []) {
        $config                 = array_merge($this->inputOptions, $options);
        $this->parts['{input}'] = Html::activeDropDownList($this->model, $this->attribute, $items, $config);
        $this->adjustLabelFor($config);
        return $this;
    }
    public function checkboxList($items = [], $options = []) {
        $this->parts['{input}'] = Html::activeCheckboxList($this->model, $this->attribute, $items, $options);
        return $this;
    }
    public function radioList($items = [], $options = []) {
        $this->parts['{input}'] = Html::activeRadioList($this->model, $this->attribute, $items, $options);
        return $this;
    }
}