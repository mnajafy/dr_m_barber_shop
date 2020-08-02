<?php
namespace App\Model;
class Test {
    public $username = 'user';
    public $password = 'pass';
    public function getAttributeLabel($attribute) {
        return $attribute;
    }
}