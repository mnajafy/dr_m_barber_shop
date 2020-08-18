<?php
namespace core\validators;
use core\helpers\StringHelper;
class NumberValidator extends Validator {
    public $integerOnly    = false;
    public $max;
    public $min;
    public $tooBig;
    public $tooSmall;
    public $integerPattern = '/^\s*[+-]?\d+\s*$/';
    public $numberPattern  = '/^\s*[-+]?[0-9]*\.?[0-9]+([eE][-+]?[0-9]+)?\s*$/';
    public function init() {
        parent::init();
        if ($this->message === null) {
            $this->message = $this->integerOnly ? '{attribute} must be an integer.' : '{attribute} must be a number.';
        }
        if ($this->min !== null && $this->tooSmall === null) {
            $this->tooSmall = '{attribute} must be no less than {min}.';
        }
        if ($this->max !== null && $this->tooBig === null) {
            $this->tooBig = '{attribute} must be no greater than {max}.';
        }
    }
    public function validateAttribute($model, $attribute) {
        $value = $model->$attribute;
        if ($this->isNotNumber($value)) {
            $this->addError($model, $attribute, $this->message);
            return;
        }
        $pattern = $this->integerOnly ? $this->integerPattern : $this->numberPattern;

        if (!preg_match($pattern, StringHelper::normalizeNumber($value))) {
            $this->addError($model, $attribute, $this->message);
        }
        if ($this->min !== null && $value < $this->min) {
            $this->addError($model, $attribute, $this->tooSmall, ['min' => $this->min]);
        }
        if ($this->max !== null && $value > $this->max) {
            $this->addError($model, $attribute, $this->tooBig, ['max' => $this->max]);
        }
    }
    private function isNotNumber($value) {
        return is_array($value) || is_bool($value) || (is_object($value) && !method_exists($value, '__toString')) || (!is_object($value) && !is_scalar($value) && $value !== null);
    }
    public function clientValidateAttribute($model, $attribute, $view) {
        ValidationAsset::register($view);
        $options = $this->getClientOptions($model, $attribute);
        return 'framework.validation.number(value, messages, ' . json_encode($options, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . ');';
    }
    public function getClientOptions($model, $attribute) {
        $label   = $model->getAttributeLabel($attribute);
        $options = [
            'pattern' => new JsExpression($this->integerOnly ? $this->integerPattern : $this->numberPattern),
            'message' => $this->formatMessage($this->message, ['attribute' => $label]),
        ];
        if ($this->min !== null) {
            $options['min']      = is_string($this->min) ? (float) $this->min : $this->min;
            $options['tooSmall'] = $this->formatMessage($this->tooSmall, ['attribute' => $label, 'min' => $this->min]);
        }
        if ($this->max !== null) {
            $options['max']    = is_string($this->max) ? (float) $this->max : $this->max;
            $options['tooBig'] = $this->formatMessage($this->tooBig, ['attribute' => $label, 'max' => $this->max]);
        }
        return $options;
    }
}