<?php
namespace core\validators;
class InlineValidator extends Validator {
    public $method;
    public function validateAttribute($model, $attribute) {
        $method = $this->method;
        if (is_string($method)) {
            $method = [$model, $method];
        }
        $method($model, $attribute, $model->$attribute);
    }
}