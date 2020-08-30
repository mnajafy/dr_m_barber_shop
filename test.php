<?php
//------------------------------------------------------------------------------
trait TestTrait {
    public function traitFunc() {
        
    }
}
trait TestTrait2 {
    public function traitFunc2() {
        
    }
}
//------------------------------------------------------------------------------
interface TestInt {
    public function interfaceFunc();
}
interface TestInt2 {
    public function interfaceFunc2();
}
//------------------------------------------------------------------------------
abstract class TestAbs implements TestInt {
    use TestTrait;
    abstract public function abstractFunc();
    public function abstractFunc2() {
        
    }
}
//------------------------------------------------------------------------------
class TestCls extends TestAbs implements TestInt2 {
    use TestTrait2;
    public function abstractFunc() {
        
    }
    public function interfaceFunc() {
        
    }
    public function interfaceFunc2() {
        
    }
}
//------------------------------------------------------------------------------
$a = new TestCls();
$a->traitFunc2();
//------------------------------------------------------------------------------

function RandomTokenDebug($length = 32){
    $randoms = array();
    if (function_exists('random_bytes')) {
        $randoms['random_bytes'] = strtr(base64_encode(random_bytes($length)), '+/', '-_');
    }
    if (function_exists('mcrypt_create_iv')) {
        $randoms['mcrypt_create_iv'] = base64_encode(mcrypt_create_iv($length, MCRYPT_DEV_URANDOM));
    }
    if (function_exists('openssl_random_pseudo_bytes')) {
        $randoms['openssl_random_pseudo_bytes'] = strtr(base64_encode(openssl_random_pseudo_bytes($length)), '+/', '-_');
    }
   
    return $randoms;
}
echo '<pre>';
print_r (RandomTokenDebug());