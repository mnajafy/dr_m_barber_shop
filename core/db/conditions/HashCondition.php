<?php
namespace core\db\conditions;
class HashCondition implements ConditionInterface {
    private $hash;
    public function __construct($hash) {
        $this->hash = $hash;
    }
    public function getHash() {
        return $this->hash;
    }
    public static function fromArrayDefinition($operator, $operands) {
        return new static($operands);
    }
}