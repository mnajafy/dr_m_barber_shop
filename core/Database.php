<?php
namespace Core;
use PDO;
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
    public function init() {
        $this->pdo = new PDO($this->dsn . ';charset=' . $this->charset, $this->username, $this->password);
    }
}