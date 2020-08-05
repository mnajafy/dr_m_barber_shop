<?php
namespace Core;
class Model extends BaseObject {
    public function attributes() {
        return [];
    }
    public function rules() {
        return [];
    }
    public function labels() {
        return [];
    }
    public function getAttributeLabel($attribute) {
        $labels = $this->labels();
        return isset($labels[$attribute]) ? $labels[$attribute] : $attribute;
    }
    public function load($data) {
        $loaded = true;
        $attributes = $this->attributes();
        foreach ($attributes as $attribute) {
            $loaded = isset($data[$attribute]) && $loaded;
            if (isset($data[$attribute])) {
                $this->$attribute = $data[$attribute];
            }
        }
        return $loaded;
    }
}