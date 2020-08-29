<?php
namespace core\helpers;
use stdClass;
use JsonSerializable;
use SimpleXMLElement;
use core\web\JsExpression;
class Json {
    public static function encode($value) {
        $expressions = [];
        $data        = static::processData($value, $expressions, uniqid('', true));
        $json        = json_encode($data);
        return $expressions === [] ? $json : strtr($json, $expressions);
    }
    public static function processData($data, &$expressions, $expPrefix) {
        if (is_object($data)) {
            if ($data instanceof JsExpression) {
                $token                           = "!{[$expPrefix=" . count($expressions) . ']}!';
                $expressions['"' . $token . '"'] = $data->expression;
                return $token;
            }
            elseif ($data instanceof JsonSerializable) {
                return static::processData($data->jsonSerialize(), $expressions, $expPrefix);
            }
            elseif ($data instanceof SimpleXMLElement) {
                $data = (array) $data;
            }
            else {
                $result = [];
                foreach ($data as $name => $value) {
                    $result[$name] = $value;
                }
                $data = $result;
            }
            if ($data === []) {
                return new stdClass();
            }
        }
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                if (is_array($value) || is_object($value)) {
                    $data[$key] = static::processData($value, $expressions, $expPrefix);
                }
            }
        }
        return $data;
    }
}