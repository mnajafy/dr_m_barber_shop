<?php
namespace core\base;
use Exception;
use ArrayObject;
use core\validators\Validator;
class Model extends BaseObject {
    /**
     * @return array
     */
    public function attributes() {
        return [];
    }
    /**
     * @return array
     */
    public function rules() {
        return [];
    }
    /**
     * @return array
     */
    public function labels() {
        return [];
    }
    /**
     * @return string
     */
    public function getAttributeLabel($attribute) {
        $labels = $this->labels();
        return isset($labels[$attribute]) ? $labels[$attribute] : $attribute;
    }
    /**
     * @return bool
     */
    public function load($data) {
        $attributes = $this->attributes();
        foreach ($attributes as $attribute) {
            if (isset($data[$attribute])) {
                $this->$attribute = $data[$attribute];
            }
        }
        return !empty($data);
    }
    //--------------------------------------------------------------------------
    private $_validators;
    private $_errors = [];
    /**
     * @return bool
     */
    public function validate($clearErrors = true) {
        if ($clearErrors) {
            $this->clearErrors();
        }
        foreach ($this->getValidators() as $validator) {
            /* @var $validator Validator */
            $validator->validateAttributes($this);
        }
        return !$this->hasErrors();
    }
    public function getValidators() {
        if ($this->_validators === null) {
            $this->_validators = $this->createValidators();
        }
        return $this->_validators;
    }
    public function createValidators() {
        $validators = new ArrayObject();
        foreach ($this->rules() as $rule) {
            if ($rule instanceof Validator) {
                $validators->append($rule);
            }
            elseif (is_array($rule) && isset($rule[0], $rule[1])) {
                $validator = Validator::createValidator($rule[1], $this, (array) $rule[0], array_slice($rule, 2));
                $validators->append($validator);
            }
            else {
                throw new Exception('Invalid validation rule: a rule must specify both attribute names and validator type.');
            }
        }
        return $validators;
    }
    public function hasErrors($attribute = null) {
        return $attribute === null ? !empty($this->_errors) : isset($this->_errors[$attribute]);
    }
    public function addError($attribute, $error) {
        $this->_errors[$attribute][] = $error;
    }
    public function addErrors($items = []) {
        foreach ($items as $attribute => $errors) {
            if (is_array($errors)) {
                foreach ($errors as $error) {
                    $this->addError($attribute, $error);
                }
            }
            else {
                $this->addError($attribute, $errors);
            }
        }
    }
    public function getErrors($attribute = null) {
        if ($attribute === null) {
            return $this->_errors;
        }
        return isset($this->_errors[$attribute]) ? $this->_errors[$attribute] : [];
    }
    public function getFirstErrors() {
        if (empty($this->_errors)) {
            return [];
        }
        $errors = [];
        foreach ($this->_errors as $name => $es) {
            if (!empty($es)) {
                $errors[$name] = reset($es);
            }
        }
        return $errors;
    }
    public function getFirstError($attribute) {
        return isset($this->_errors[$attribute]) ? reset($this->_errors[$attribute]) : null;
    }
    public function clearErrors($attribute = null) {
        if ($attribute === null) {
            $this->_errors = [];
        }
        else {
            unset($this->_errors[$attribute]);
        }
    }
}