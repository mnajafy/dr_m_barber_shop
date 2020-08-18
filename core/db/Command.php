<?php
namespace core\db;
use PDO;
use Framework;
use core\base\BaseObject;
class Command extends BaseObject {
    /**
     * 
     */
    public $sql;
    /**
     * 
     */
    public $params = [];
    /**
     * 
     */
    protected function queryInternal($method, $fetchMode = PDO::FETCH_ASSOC) {
        $statement = Framework::$app->getDb()->pdo->prepare($this->sql);
        foreach ($this->params as $key => $value) {
            $statement->bindValue($key, $value);
        }
        $result = false;
        if ($statement->execute()) {
            $result = $statement->$method($fetchMode);
            $statement->closeCursor();
        }
        return $result;
    }
    /**
     * 
     */
    public function queryOne() {
        return $this->queryInternal('fetch');
    }
    /**
     * 
     */
    public function queryAll() {
        return $this->queryInternal('fetchAll');
    }
    /**
     * 
     */
    public function queryScalar() {
        return $this->queryInternal('fetchColumn', 0);
    }
    /**
     * @return bool
     */
    public function execute() {
        $db        = Framework::$app->getDb();
        $statement = $db->pdo->prepare($this->sql);
        foreach ($this->params as $key => $value) {
            $statement->bindValue($key, $value);
        }
        $result = $statement->execute();
        $statement->closeCursor();
        return $result;
    }
}