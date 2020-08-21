<?php
namespace core\helpers;
use Exception;
use Framework;
use core\web\Application;
class Url {
    public static function normalizeRoute($route) {
        $route = Framework::getAlias((string) $route);
        if (strncmp($route, '/', 1) === 0) {
            return ltrim($route, '/');
        }
        // relative route
        if (Framework::$app->controller === null) {
            throw new Exception("Unable to resolve the relative route: $route. No active controller is available.");
        }
        if (strpos($route, '/') === false) {
            // empty or an action ID
            return $route === '' ? Framework::$app->controller->getRoute() : Framework::$app->controller->getUniqueId() . '/' . $route;
        }
        if (Framework::$app->controller->module instanceof Application) {
            return ltrim($route, '/');
        }
        return ltrim(Framework::$app->controller->module->getUniqueId() . '/' . $route, '/');
    }
    public static function toRoute($route = []) {
        $route[0] = static::normalizeRoute($route[0]);
        return Framework::$app->getUrlManager()->createUrl($route);
    }
    public static function to($url = []) {
        if (is_array($url)) {
            return static::toRoute($url);
        }
        $url = Framework::getAlias($url);
        if (!$url) {
            $url = Framework::$app->getRequest()->getUrl();
        }
        return $url;
    }
}