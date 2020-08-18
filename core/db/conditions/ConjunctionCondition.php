<?php
namespace core\db\conditions;
abstract class ConjunctionCondition implements ConditionInterface {
    protected $expressions;
    public function __construct($expressions) {
        $this->expressions = $expressions;
    }
    public function getExpressions() {
        return $this->expressions;
    }
    abstract public function getOperator();
    public static function fromArrayDefinition($operator, $operands) {
        return new static($operands);
    }
}