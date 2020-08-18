<?php
namespace core\db\mysql;
use Framework;
class QueryBuilder extends \core\db\QueryBuilder {
    /**
     * @param \core\db\ActiveQuery $query
     */
    public function select($query) {

        $query->prepare($this);

        $params  = [];
        $clauses = [
            $this->buildSelect($query->select),
            $this->buildFrom($query->from),
            $this->buildWhere($query->where, $params),
            $this->buildGroupBy($query->groupBy),
            $this->buildOrderBy($query->orderBy),
            $this->buildLimitAndOffset($query->limit, $query->offset),
        ];

        $sql = implode($this->separator, $clauses);
        return [$sql, $params];
    }
    /**
     * 
     */
    public function delete($table, $condition, &$params) {
        $where = $this->buildWhere($condition, $params);
        $sql   = '';
        $sql   .= ' DELETE FROM ' . $table;
        $sql   .= ' ' . $where;
        return $sql;
    }
    /**
     * 
     */
    public function insert($table, $columns, &$params) {
        list($names, $placeholders) = $this->prepareInsertValues($table, $columns, $params);
        $sql = '';
        $sql .= ' INSERT INTO ' . $table;
        $sql .= (!empty($names) ? ' (' . implode(', ', $names) . ')' : '');
        $sql .= (!empty($placeholders) ? ' VALUES (' . implode(', ', $placeholders) . ')' : ' DEFAULT VALUES');
        return $sql;
    }
    /**
     * 
     */
    public function update($table, $columns, $condition, &$params) {
        $sets  = $this->prepareUpdateSets($table, $columns, $params);
        $where = $this->buildWhere($condition, $params);
        $sql   = '';
        $sql   .= ' UPDATE ' . $table;
        $sql   .= ' SET ' . implode(', ', $sets);
        $sql   .= ' ' . $where;
        return $sql;
    }
    /**
     * 
     */
    protected function prepareInsertValues($table, $columns, &$params) {
        $schema        = Framework::$app->getDb()->getSchema();
        $tableSchema   = $schema->getTableSchema($table);
        $columnSchemas = $tableSchema !== null ? $tableSchema->columns : [];
        $names         = [];
        $placeholders  = [];

        foreach ($columns as $name => $value) {
            $names[]        = $name;
            $value          = isset($columnSchemas[$name]) ? $columnSchemas[$name]->dbTypecast($value) : $value;
            $placeholders[] = $this->bindParam($value, $params);
        }

        return [$names, $placeholders];
    }
    /**
     * 
     */
    protected function prepareUpdateSets($table, $columns, &$params) {
        $schema        = Framework::$app->getDb()->getSchema();
        $tableSchema   = $schema->getTableSchema($table);
        $columnSchemas = $tableSchema !== null ? $tableSchema->columns : [];
        $sets          = [];
        foreach ($columns as $name => $value) {
            $value       = isset($columnSchemas[$name]) ? $columnSchemas[$name]->dbTypecast($value) : $value;
            $placeholder = $this->bindParam($value, $params);
            $sets[]      = $name . '=' . $placeholder;
        }
        return $sets;
    }
    //
    protected function buildSelect($columns) {
        if (empty($columns)) {
            return 'SELECT *';
        }
        return 'SELECT ' . implode(', ', $columns);
    }
    protected function buildFrom($tables) {
        if (empty($tables)) {
            return '';
        }
        return 'FROM ' . implode(', ', $tables);
    }
    protected function buildWhere($condition, &$params) {
        if (empty($condition)) {
            return null;
        }
        $where = $this->buildCondition($condition, $params);
        return $where === '' ? '' : 'WHERE ' . $where;
    }
    protected function buildGroupBy($columns) {
        if (empty($columns)) {
            return '';
        }
        return 'GROUP BY ' . implode(', ', $columns);
    }
    protected function buildOrderBy($columns) {
        if (empty($columns)) {
            return '';
        }
        $sql = [];
        foreach ($columns as $key => $value) {
            $sql[] = $key . ($value === SORT_ASC ? ' ASC' : ' DESC');
        }
        return 'ORDER BY ' . implode(', ', $sql);
    }
    protected function buildLimitAndOffset($limit, $offset) {
        if ($limit === null) {
            return '';
        }
        $sql = 'LIMIT ' . $limit;
        if ($offset !== null) {
            $sql .= ' OFFSET ' . $offset;
        }
        return $sql;
    }
}