<?php
namespace core\db;
use PDO;
use Exception;
use core\base\BaseObject;
/**
 * Database
 * 
 * @property string $dsn
 * @property string $username
 * @property string $password
 * @property string $charset
 */
class Database extends BaseObject {
    /**
     * @var PDO
     */
    public $pdo;
    public $schemaMap = [
        'mysql'  => 'core\db\mysql\Schema', // MySQL
        'pgsql'  => 'core\db\pgsql\Schema', // PostgreSQL
    ];
    public function init() {
        $this->pdo = new PDO($this->dsn . ';charset=' . $this->charset, $this->username, $this->password);
    }
    private $_driverName;
    /**
     * @return string name of the DB driver
     */
    public function getDriverName() {
        if ($this->_driverName === null) {
            if (($pos = strpos($this->dsn, ':')) !== false) {
                $this->_driverName = strtolower(substr($this->dsn, 0, $pos));
            }
            else {
                $this->_driverName = strtolower($this->pdo->getAttribute(PDO::ATTR_DRIVER_NAME));
            }
        }
        return $this->_driverName;
    }
    private $_schema;
    /**
     * @return Schema
     */
    public function getSchema() {
        if ($this->_schema === null) {
            $driver = $this->getDriverName();
            if (!isset($this->schemaMap[$driver])) {
                throw new Exception("Connection does not support reading schema information for '$driver' DBMS.");
            }
            $config        = ['class' => $this->schemaMap[$driver]];
            $this->_schema = BaseObject::createObject($config);
        }
        return $this->_schema;
    }
    /**
     * @return Command
     */
    public function createCommand($sql = null, $params = []) {
        return BaseObject::createObject(['class' => Command::class, 'sql' => $sql, 'params' => $params]);
    }
}