<?php

Class Mysql
{
    private static $conn = null;
    private function __construct(){}

    public static function getInstance($host='', $username='', $password='', $dbname=''){
        if(self::$conn === null){
            self::$conn = new mysqli($host, $username, $password, $dbname);
        }
        return self::$conn;
    }


    public function query($sql)
    {
        $result = $this->conn->query($sql);
        return $result;
    }
}