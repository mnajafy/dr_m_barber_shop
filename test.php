<?php
//echo 'Tester';
//preg_match_all('/<([\w._-]+)>/', '<module>/<id>', $matches);
//preg_match('#^(?P<a4cf2669a>[^\/]+)/(?P<a47cc8c92>[^\/]+)$#u', 'aa/bb/1', $matches);
//echo '<pre>';
//var_dump($matches);
//$str = '/<controller>/<action>/<id>/';
//echo strtr($str, [
//    '<controller>' => 'asd'
//]);
//$a = explode('/', 'aa/bb/cc', 2);
//var_dump($a);
function merge($a, $b) {
    $args = func_get_args();
    $res  = array_shift($args);
    while (!empty($args)) {
        foreach (array_shift($args) as $k => $v) {
            if (is_int($k)) {
                if (array_key_exists($k, $res)) {
                    $res[] = $v;
                }
                else {
                    $res[$k] = $v;
                }
            }
            elseif (is_array($v) && isset($res[$k]) && is_array($res[$k])) {
                $res[$k] = merge($res[$k], $v);
            }
            else {
                $res[$k] = $v;
            }
        }
    }
    return $res;
}
$core   = [
    'request'    => ['class' => '\core\web\Request', 'b' => ['a' => 'a']],
    'response'   => ['class' => '\core\web\Response'],
    'urlManager' => ['class' => '\core\web\UrlManager'],
    'session'    => ['class' => '\core\web\Session'],
    'cookie'     => ['class' => '\core\web\Cookie'],
    'view'       => ['class' => '\core\web\View'],
    'user'       => ['class' => '\core\web\User'],
    'db'         => ['class' => '\core\db\Database'],
];
$config = merge($core, [
    'request' => [
        'b' => [
            'a' => 'b'
        ]
    ]
]);

echo '<pre>';
var_dump($config);
