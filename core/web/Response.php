<?php
namespace core\web;
use Framework;
use core\base\BaseObject;
class Response extends BaseObject {
    public $code = 200;
    public $data;
    public function send() {
        http_response_code($this->code);
        echo $this->data;
    }
    public function redirect($url, $statusCode = 302, $checkAjax = true) {
//        if (is_array($url) && isset($url[0])) {
//            // ensure the route is absolute
//            $url[0] = '/' . ltrim($url[0], '/');
//        }
//        $request = Framework::$app->getRequest();
//        $url     = Url::to($url);
//        if (strncmp($url, '/', 1) === 0 && strncmp($url, '//', 2) !== 0) {
//            $url = $request->getHostInfo() . $url;
//        }
//        if ($checkAjax) {
//            if ($request->getIsAjax()) {
//                if (in_array($statusCode, [301, 302]) && preg_match('/Trident\/|MSIE[ ]/', $request->userAgent)) {
//                    $statusCode = 200;
//                }
//                if ($request->getIsPjax()) {
//                    $this->getHeaders()->set('X-Pjax-Url', $url);
//                }
//                else {
//                    $this->getHeaders()->set('X-Redirect', $url);
//                }
//            }
//            else {
//                $this->getHeaders()->set('Location', $url);
//            }
//        }
//        else {
//        }
//
//        $this->getHeaders()->set('Location', $url);
//        $this->setStatusCode($statusCode);
//        return $this;
        header("Location:$url");
        exit;
    }
}