<?php
namespace core\db\conditions;
use core\db\ExpressionInterface;
interface ConditionInterface extends ExpressionInterface {
    /**
     * Creates object by array-definition as described in
     * [Query Builder – Operator format](guide:db-query-builder#operator-format) guide article.
     *
     * @param string $operator operator in uppercase.
     * @param array $operands array of corresponding operands
     *
     * @return $this
     */
    public static function fromArrayDefinition($operator, $operands);
}