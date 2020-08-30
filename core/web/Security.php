<?php
namespace core\web;
use Exception;
use core\base\BaseObject;
use core\helpers\StringHelper;
class Security extends BaseObject {
    public function generateRandomKey($length = 32) {
        if (!is_int($length)) {
            throw new Exception('First parameter ($length) must be an integer');
        }
        if ($length < 1) {
            throw new Exception('First parameter ($length) must be greater than 0');
        }
        if (function_exists('random_bytes')) {
            return random_bytes($length);
        }
        if (function_exists('mcrypt_create_iv')) {
            return mcrypt_create_iv($length, MCRYPT_DEV_URANDOM);
        }
        if (function_exists('openssl_random_pseudo_bytes')) {
            return openssl_random_pseudo_bytes($length);
        }
        throw new Exception('Unable to generate a random key');
    }
    public function generateRandomString($length = 32) {
        if (!is_int($length)) {
            throw new Exception('First parameter ($length) must be an integer');
        }
        if ($length < 1) {
            throw new Exception('First parameter ($length) must be greater than 0');
        }
        $bytes = $this->generateRandomKey($length);
        return substr(StringHelper::base64UrlEncode($bytes), 0, $length);
    }
    public function compareString($expected, $actual) {
        if (!is_string($expected)) {
            throw new Exception('Expected expected value to be a string, ' . gettype($expected) . ' given.');
        }
        if (!is_string($actual)) {
            throw new Exception('Expected actual value to be a string, ' . gettype($actual) . ' given.');
        }
        if (function_exists('hash_equals')) {
            return hash_equals($expected, $actual);
        }
        $expected       .= "\0";
        $actual         .= "\0";
        $expectedLength = StringHelper::byteLength($expected);
        $actualLength   = StringHelper::byteLength($actual);
        $diff           = $expectedLength - $actualLength;
        for ($i = 0; $i < $actualLength; $i++) {
            $diff |= (ord($actual[$i]) ^ ord($expected[$i % $expectedLength]));
        }
        return $diff === 0;
    }
    public function maskToken($token) {
        $mask = $this->generateRandomKey(StringHelper::byteLength($token));
        return StringHelper::base64UrlEncode($mask . ($mask ^ $token));
    }
    public function unmaskToken($maskedToken) {
        $decoded = StringHelper::base64UrlDecode($maskedToken);
        $length  = StringHelper::byteLength($decoded) / 2;
        if (!is_int($length)) {
            return '';
        }
        return StringHelper::byteSubstr($decoded, $length, $length) ^ StringHelper::byteSubstr($decoded, 0, $length);
    }
}