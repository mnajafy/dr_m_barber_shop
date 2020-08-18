<?php
namespace core\db\conditions;
use Exception;
class SimpleCondition implements ConditionInterface {
    private $operator;
    private $column;
    private $value;
    public function __construct($column, $operator, $value) {
        $this->column   = $column;
        $this->operator = $operator;
        $this->value    = $value;
    }
    public function getOperator() {
        return $this->operator;
    }
    public function getColumn() {
        return $this->column;
    }
    public function getValue() {
        return $this->value;
    }
    public static function fromArrayDefinition($operator, $operands) {
        if (count($operands) !== 2) {
            throw new Exception("Operator '$operator' requires two operands.");
        }
        return new static($operands[0], $operator, $operands[1]);
    }
}