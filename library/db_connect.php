<?php
class DB
{

    protected static $instance;

    public static function getInstance()
    {
        if (null === static::$instance) {
            static::$instance = new PDO('mysql:host=localhost; dbname=roz', "root", "",
                array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
        }
        return static::$instance;
    }


    private function __construct()
    {
    }


    private function __clone()
    {
    }


    private function __wakeup()
    {
    }
}
?>