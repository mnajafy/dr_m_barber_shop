<?php
namespace Core;
class Url extends BaseObject {
    public static function to($params = []) {
        if (!isset($params[0])) {
            throw new Exception('url to exception');
        }
        $key   = null;
        $rules = \Framework::$app->urlManager->rules;
        foreach ($rules as $index => $value) {
            if ($value == $params[0]) {
                $key = $index;
                break;
            }
        }
        if ($key == null) {
            throw new Exception('url to exception');
        }
        unset($params[0]);
        $hash = null;
        if (isset($params['#'])) {
            $hash = $params['#'];
            unset($params['#']);
        }
        $query = http_build_query($params);
        return \Framework::getAlias("@web/$key" . ($query ? '?' . $query : '') . ($hash ? "#$hash" : ''));
    }
}