<?php
namespace core\db\mysql;
use PDO;
use Framework;
use core\db\TableSchema;
use core\base\BaseObject;
class Schema extends \core\db\Schema {
    
    public $typeMap = [
        'tinyint' => self::TYPE_TINYINT,
        'bit' => self::TYPE_INTEGER,
        'smallint' => self::TYPE_SMALLINT,
        'mediumint' => self::TYPE_INTEGER,
        'int' => self::TYPE_INTEGER,
        'integer' => self::TYPE_INTEGER,
        'bigint' => self::TYPE_BIGINT,
        'float' => self::TYPE_FLOAT,
        'double' => self::TYPE_DOUBLE,
        'real' => self::TYPE_FLOAT,
        'decimal' => self::TYPE_DECIMAL,
        'numeric' => self::TYPE_DECIMAL,
        'tinytext' => self::TYPE_TEXT,
        'mediumtext' => self::TYPE_TEXT,
        'longtext' => self::TYPE_TEXT,
        'longblob' => self::TYPE_BINARY,
        'blob' => self::TYPE_BINARY,
        'text' => self::TYPE_TEXT,
        'varchar' => self::TYPE_STRING,
        'string' => self::TYPE_STRING,
        'char' => self::TYPE_CHAR,
        'datetime' => self::TYPE_DATETIME,
        'year' => self::TYPE_DATE,
        'date' => self::TYPE_DATE,
        'time' => self::TYPE_TIME,
        'timestamp' => self::TYPE_TIMESTAMP,
        'enum' => self::TYPE_STRING,
        'varbinary' => self::TYPE_BINARY,
        'json' => self::TYPE_JSON,
    ];
    /**
     * 
     */
    public function getQueryBuilder() {
        if ($this->_builder === null) {
            $this->_builder = BaseObject::createObject(['class' => QueryBuilder::class]);
        }
        return $this->_builder;
    }
    /**
     * 
     */
    protected function loadTableSchema($name) {
        $table       = new TableSchema();
        $table->name = $name;
        $this->findColumns($table);
        $this->findConstraints($table);
        return $table;
    }
    /**
     * @param TableSchema $table
     */
    protected function findColumns($table) {
        $db      = Framework::$app->db;
        $sql     = 'SHOW FULL COLUMNS FROM ' . $table->name;
        $columns = $db->createCommand($sql)->queryAll();
        foreach ($columns as $info) {
            if ($db->pdo->getAttribute(PDO::ATTR_CASE) !== PDO::CASE_LOWER) {
                $info = array_change_key_case($info, CASE_LOWER);
            }
            $column                        = $this->loadColumnSchema($info);
            $table->columns[$column->name] = $column;
            if ($column->isPrimaryKey) {
                $table->primaryKey[] = $column->name;
            }
        }
    }
    /**
     * @param TableSchema $table
     */
    protected function findConstraints($table) {
        $sql         = '
            SELECT
                kcu.constraint_name,
                kcu.column_name,
                kcu.referenced_table_name,
                kcu.referenced_column_name
            FROM information_schema.referential_constraints AS rc
            JOIN information_schema.key_column_usage AS kcu ON
                (
                    kcu.constraint_catalog = rc.constraint_catalog OR
                    (kcu.constraint_catalog IS NULL AND rc.constraint_catalog IS NULL)
                ) AND
                kcu.constraint_schema = rc.constraint_schema AND
                kcu.constraint_name = rc.constraint_name
            WHERE rc.constraint_schema = database() AND kcu.table_schema = database()
            AND rc.table_name = :tableName AND kcu.table_name = :tableName1
        ';
        $rows        = Framework::$app->db->createCommand($sql, [':tableName' => $table->name, ':tableName1' => $table->name])->queryAll();
        $constraints = [];
        foreach ($rows as $row) {
            $constraints[$row['constraint_name']]['referenced_table_name']        = $row['referenced_table_name'];
            $constraints[$row['constraint_name']]['columns'][$row['column_name']] = $row['referenced_column_name'];
        }
        $table->foreignKeys = [];
        foreach ($constraints as $name => $constraint) {
            $table->foreignKeys[$name] = array_merge([$constraint['referenced_table_name']], $constraint['columns']);
        }
    }
    /**
     * @return ColumnSchema
     */
    protected function loadColumnSchema($info) {
        $column                = new ColumnSchema();
        $column->name          = $info['field'];
        $column->allowNull     = $info['null'] === 'YES';
        $column->isPrimaryKey  = strpos($info['key'], 'PRI') !== false;
        $column->autoIncrement = stripos($info['extra'], 'auto_increment') !== false;
        $column->comment       = $info['comment'];
        $column->dbType        = $info['type'];
        $column->unsigned      = stripos($column->dbType, 'unsigned') !== false;
        $column->type = self::TYPE_STRING;
        if (preg_match('/^(\w+)(?:\(([^\)]+)\))?/', $column->dbType, $matches)) {
            $type = strtolower($matches[1]);
            if (isset($this->typeMap[$type])) {
                $column->type = $this->typeMap[$type];
            }
            if (!empty($matches[2])) {
                if ($type === 'enum') {
                    preg_match_all("/'[^']*'/", $matches[2], $values);
                    foreach ($values[0] as $i => $value) {
                        $values[$i] = trim($value, "'");
                    }
                    $column->enumValues = $values;
                }
                else {
                    $values            = explode(',', $matches[2]);
                    $column->size      = $column->precision = (int) $values[0];
                    if (isset($values[1])) {
                        $column->scale = (int) $values[1];
                    }
                    if ($column->size === 1 && $type === 'bit') {
                        $column->type = 'boolean';
                    }
                    elseif ($type === 'bit') {
                        if ($column->size > 32) {
                            $column->type = 'bigint';
                        }
                        elseif ($column->size === 32) {
                            $column->type = 'integer';
                        }
                    }
                }
            }
        }
        $column->phpType = $this->getColumnPhpType($column);
        if (!$column->isPrimaryKey) {
            if (($column->type === 'timestamp' || $column->type === 'datetime') && preg_match('/^current_timestamp(?:\(([0-9]*)\))?$/i', $info['default'], $matches)) {
//                $column->defaultValue = new Expression('CURRENT_TIMESTAMP' . (!empty($matches[1]) ? '(' . $matches[1] . ')' : ''));
            }
            elseif (isset($type) && $type === 'bit') {
                $column->defaultValue = bindec(trim($info['default'], 'b\''));
            }
            else {
                $column->defaultValue = $column->phpTypecast($info['default']);
            }
        }
        return $column;
    }
    protected function getColumnPhpType($column) {
        static $typeMap = [
            self::TYPE_TINYINT  => 'integer',
            self::TYPE_SMALLINT => 'integer',
            self::TYPE_INTEGER  => 'integer',
            self::TYPE_BIGINT   => 'integer',
            self::TYPE_BOOLEAN  => 'boolean',
            self::TYPE_FLOAT    => 'double',
            self::TYPE_DOUBLE   => 'double',
            self::TYPE_BINARY   => 'resource',
            self::TYPE_JSON     => 'array',
        ];
        if (isset($typeMap[$column->type])) {
            if ($column->type === 'bigint') {
                return PHP_INT_SIZE === 8 && !$column->unsigned ? 'integer' : 'string';
            }
            elseif ($column->type === 'integer') {
                return PHP_INT_SIZE === 4 && $column->unsigned ? 'string' : 'integer';
            }
            return $typeMap[$column->type];
        }
        return 'string';
    }
}