<?php
namespace core\validators;
class BooleanValidator extends Validator {
    public $trueValue  = '1';
    public $falseValue = '0';
    public $strict     = false;
    public function init() {
        parent::init();
        if ($this->message === null) {
            $this->message = '{attribute} must be either "{true}" or "{false}".';
        }
    }
    protected function validateValue($value) {
        if ($this->strict) {
            $valid = $value === $this->trueValue || $value === $this->falseValue;
        }
        else {
            $valid = $value == $this->trueValue || $value == $this->falseValue;
        }
        if ($valid) {
            return [];
        }
        return [$this->message, [
                'true'  => $this->trueValue === true ? 'true' : $this->trueValue,
                'false' => $this->falseValue === false ? 'false' : $this->falseValue,
        ]];
    }
    public function clientValidateAttribute($model, $attribute, $view) {
        ValidationAsset::register($view);
        $options = $this->getClientOptions($model, $attribute);
        return 'framework.validation.boolean(value, messages, ' . json_encode($options, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . ');';
    }
    public function getClientOptions($model, $attribute) {
        $options = [
            'trueValue'  => $this->trueValue,
            'falseValue' => $this->falseValue,
            'message'    => $this->formatMessage($this->message, [
                'attribute' => $model->getAttributeLabel($attribute),
                'true'      => $this->trueValue === true ? 'true' : $this->trueValue,
                'false'     => $this->falseValue === false ? 'false' : $this->falseValue,
            ]),
        ];
        if ($this->strict) {
            $options['strict'] = 1;
        }
        return $options;
    }
}