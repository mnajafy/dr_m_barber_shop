<?php
namespace Core;
/**
 * Response
 */
class Response extends BaseObject {
    public $code = 200;
    public $data;
    public function send() {
        http_response_code($this->code);
        echo $this->data;
    }
}