<?php
namespace core\db;
use Exception;
use core\base\BaseObject;
use core\db\conditions\HashCondition;
class QueryBuilder extends BaseObject {
    const PARAM_PREFIX                  = ':qp';
    public $separator             = ' ';
    protected $expressionBuilders = [];
    protected $conditionClasses   = [];
    /**
     * 
     */
    public function init() {
        parent::init();
        $this->conditionClasses   = array_merge($this->defaultConditionClasses(), $this->conditionClasses);
        $this->expressionBuilders = array_merge($this->defaultExpressionBuilders(), $this->expressionBuilders);
    }
    /**
     * @return array Default Condition Classes
     */
    protected function defaultConditionClasses() {
        return [
            'AND'         => 'core\db\conditions\AndCondition',
            'OR'          => 'core\db\conditions\OrCondition',
            //
            'NOT'         => 'core\db\conditions\NotCondition',
            'BETWEEN'     => 'core\db\conditions\BetweenCondition',
            'NOT BETWEEN' => 'core\db\conditions\BetweenCondition',
            'IN'          => 'core\db\conditions\InCondition',
            'NOT IN'      => 'core\db\conditions\InCondition',
            'LIKE'        => 'core\db\conditions\LikeCondition',
            'NOT LIKE'    => 'core\db\conditions\LikeCondition',
            'OR LIKE'     => 'core\db\conditions\LikeCondition',
            'OR NOT LIKE' => 'core\db\conditions\LikeCondition',
            'EXISTS'      => 'core\db\conditions\ExistsCondition',
            'NOT EXISTS'  => 'core\db\conditions\ExistsCondition',
        ];
    }
    /**
     * @return array Default Expression Builders
     */
    protected function defaultExpressionBuilders() {
        return [
            'core\db\conditions\SimpleCondition'         => 'core\db\conditions\SimpleConditionBuilder',
            'core\db\conditions\HashCondition'           => 'core\db\conditions\HashConditionBuilder',
            'core\db\conditions\AndCondition'            => 'core\db\conditions\ConjunctionConditionBuilder',
            'core\db\conditions\OrCondition'             => 'core\db\conditions\ConjunctionConditionBuilder',
            'core\db\conditions\ConjunctionCondition'    => 'core\db\conditions\ConjunctionConditionBuilder',
            //
            'core\db\conditions\NotCondition'            => 'core\db\conditions\NotConditionBuilder',
            'core\db\conditions\InCondition'             => 'core\db\conditions\InConditionBuilder',
            'core\db\conditions\LikeCondition'           => 'core\db\conditions\LikeConditionBuilder',
            'core\db\conditions\ExistsCondition'         => 'core\db\conditions\ExistsConditionBuilder',
            'core\db\conditions\BetweenCondition'        => 'core\db\conditions\BetweenConditionBuilder',
            'core\db\conditions\BetweenColumnsCondition' => 'core\db\conditions\BetweenColumnsConditionBuilder',
            //
            'core\db\Query'                              => 'core\db\QueryExpressionBuilder',
            'core\db\PdoValue'                           => 'core\db\PdoValueBuilder',
            'core\db\Expression'                         => 'core\db\ExpressionBuilder',
        ];
    }
    //
    /**
     * @param ActiveQuery $query
     */
    public function select($query) {
        
    }
    /**
     * 
     */
    public function delete($table, $condition, &$params) {
        
    }
    /**
     * 
     */
    public function insert($table, $columns, &$params) {
        
    }
    /**
     * 
     */
    public function update($table, $columns, $condition, &$params) {
        
    }
    //
    /**
     * 
     */
    public function bindParam($value, &$params) {
        $phName          = self::PARAM_PREFIX . count($params);
        $params[$phName] = $value;
        return $phName;
    }
    /**
     * 
     */
    public function buildCondition($condition, &$params) {
        if (is_array($condition)) {
            if (empty($condition)) {
                return '';
            }
            $condition = $this->createConditionFromArray($condition);
        }
        if ($condition instanceof ExpressionInterface) {
            return $this->buildExpression($condition, $params);
        }
        return (string) $condition;
    }
    /**
     * 
     */
    public function createConditionFromArray($condition) {
        if (isset($condition[0])) {
            $operator  = strtoupper(array_shift($condition));
            /* @var $className \core\db\conditions\ConditionInterface */
            $className = 'core\db\conditions\SimpleCondition';
            if (isset($this->conditionClasses[$operator])) {
                $className = $this->conditionClasses[$operator];
            }
            return $className::fromArrayDefinition($operator, $condition);
        }
        return new HashCondition($condition);
    }
    /**
     * 
     */
    public function buildExpression(ExpressionInterface $expression, &$params = []) {
        $builder = $this->getExpressionBuilder($expression);
        return $builder->build($expression, $params);
    }
    /**
     * @return ExpressionBuilderInterface
     */
    public function getExpressionBuilder(ExpressionInterface $expression) {
        $className = get_class($expression);
        if (!isset($this->expressionBuilders[$className])) {
            foreach (array_reverse($this->expressionBuilders) as $expressionClass => $builderClass) {
                if (is_subclass_of($expression, $expressionClass)) {
                    $this->expressionBuilders[$className] = $builderClass;
                    break;
                }
            }

            if (!isset($this->expressionBuilders[$className])) {
                throw new Exception('Expression of class ' . $className . ' can not be built in ' . get_class($this));
            }
        }

        if ($this->expressionBuilders[$className] === __CLASS__) {
            return $this;
        }

        if (!is_object($this->expressionBuilders[$className])) {
            $this->expressionBuilders[$className] = new $this->expressionBuilders[$className]($this);
        }

        return $this->expressionBuilders[$className];
    }
}