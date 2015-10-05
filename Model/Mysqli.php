<?php
namespace Model;

class Mysqli{

    private $login;

    private $password;

    private $database;

    static protected $mysqli;

    public function connect(){
        $this->login = 'root';
        $this->password = '3245897';
        $this->database = 'aikido';

        self::$mysqli = new \mysqli('localhost', $this->login, $this->password, $this->database);
        self::$mysqli->set_charset("utf8");

        if (self::$mysqli->connect_error) {
            mail('tulupov.m@gmail.com','AIKIDO LOG','ERROR:Driver'.'Connect Error (' . self::$mysqli->connect_errno . ') ' . self::$mysqli->connect_error );
            exit;
        }
    }

}