<?php

namespace Core;

use \PDO;

class Db
{ 
    public static $_pdo;
    private static $_dsn;
    private static $_username;
    private static $_password;
    private static $_charset;

    private static function getDb()
    {
        $data = require_once('app/config/db.php');

        self::$_dsn = $data['dsn'];
        self::$_username = $data['username'];
        self::$_password = $data['password'];
        self::$_charset = $data['charset'];
    }

    public static function connect()
    {
        if (self::$_pdo === null) 
        {
            self::getDb();
            try 
            {
                $pdo = new PDO(self::$_dsn . self::$_charset, self::$_username, self::$_password);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$_pdo = $pdo;
            } 
            catch (Exception $e)
            {
                die('Error : ' . $e->getMessage());
            }
        }
        return self::$_pdo;
    }

    public static function prepare($statement, array $driver_options, $class_name, $fetch = false, $one = false)
    {
        $prepare = self::connect()->prepare($statement);
        $prepare->execute($driver_options);
        $prepare->setFetchMode(PDO::FETCH_CLASS, $class_name);
        if ($fetch)
        {
            if ($one)
            {
                return $prepare->fetch();
            }
            else
            {
                return $prepare->fetchAll();
            }
        }
        
    }

    public static function query($statement, $class_name, $one = false)
    {
        $query = self::connect()->query($statement);
        $query->setFetchMode(PDO::FETCH_CLASS, $class_name);
        if ($one)
        {
            return $query->fetch();
        }
        else
        {
            return $query->fetchAll();
        }
    }
}

?>