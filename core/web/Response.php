<?php
namespace core\web;
use core\base\BaseObject;
class Response extends BaseObject {
    public $code = 200;
    public $data;
    public function send() {
        http_response_code($this->code);
        echo $this->data;
    }
}