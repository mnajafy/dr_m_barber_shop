<?php
namespace core\validators;
class RequiredValidator extends Validator {
    public $requiredValue;
    public $strict = false;
    public $message;
    public function init() {
        parent::init();
        if ($this->message === null) {
            $this->message = $this->requiredValue === null ? '{attribute} cannot be blank.' : '{attribute} must be "{requiredValue}".';
        }
    }
    public function validateValue($value) {
        if ($this->requiredValue === null) {
            if ($this->strict && $value !== null || !$this->strict && !$this->isEmpty(is_string($value) ? trim($value) : $value)) {
                return [];
            }
        }
        elseif (!$this->strict && $value == $this->requiredValue || $this->strict && $value === $this->requiredValue) {
            return [];
        }
        if ($this->requiredValue === null) {
            return [$this->message, []];
        }
        return [$this->message, ['requiredValue' => $this->requiredValue]];
    }
    public function clientValidateAttribute($model, $attribute, $view) {
        ValidationAsset::register($view);
        $options = $this->getClientOptions($model, $attribute);

        return 'framework.validation.required(value, messages, ' . json_encode($options, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . ');';
    }
    public function getClientOptions($model, $attribute) {
        $options = [];
        if ($this->requiredValue !== null) {
            $options['message']       = $this->formatMessage($this->message, [
                'requiredValue' => $this->requiredValue,
            ]);
            $options['requiredValue'] = $this->requiredValue;
        }
        else {
            $options['message'] = $this->message;
        }
        $options['strict'] = ($this->strict ? 1 : 0);
        $options['message'] = $this->formatMessage($options['message'], [
            'attribute' => $model->getAttributeLabel($attribute),
        ]);

        return $options;
    }
    public function isEmpty($value) {
        return $value === null || $value === [] || $value === '';
    }
}