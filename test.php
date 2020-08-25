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