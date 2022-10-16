<?php

namespace lib;


namespace lib;

class Db
{
    private static $instance = null;
    private $db;

    private function __construct()
    {
        $config = require 'config/db.php';
        $options = [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC
        ];
        $this->db = new \PDO($config['dsn'], $config['user'], $config['pass'], $options);
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new Db();
        }

        return self::$instance;
    }

    public function getConnection()
    {
        return $this->db;
    }

    public function query($sql,$params = [])
    {
        $db = $this->getConnection();

        $stmt = $db->prepare($sql);
        if(!empty($params)){
            foreach ($params as $key=>$value){
                $stmt->bindValue(':'.$key,$value);
            }
        }
        $stmt->execute();
        return $stmt;
    }

    public function row($sql,$params = [])
    {
        $result = $this->query($sql,$params);
        return $result->fetch(\PDO::FETCH_ASSOC);
    }

    public function getAllRows($sql,$params = [])
    {
        $result = $this->query($sql,$params);
        return $result->fetchAll(\PDO::FETCH_ASSOC);
    }
}
