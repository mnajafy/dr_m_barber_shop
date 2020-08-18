<?php
namespace app;
class In extends \core\validators\Validator {
    public function validateAttribute($model, $attribute) {
        if ($attribute === 'username') {
            $result = $this->validateValue($model->$attribute);
            if (!empty($result)) {
                $this->addError($model, $attribute, $result[0], $result[1]);
            }
        }
    }
    public function validateValue($value) {
        return ['asds', []];
    }
}