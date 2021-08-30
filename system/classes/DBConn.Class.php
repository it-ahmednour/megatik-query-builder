<?php
class DBConn extends PDO {	
    
    private static $hostname = 'localhost';
    private static $database = 'test';
    private static $username = 'root';
    private static $password = '';
    protected static $instance = NULL;

    private function __construct(){ }

    public static function getInstance() {
        if(null === self::$instance){
            try {
                $opt = array (
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_EMULATE_PREPARES => false,
                    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                );
                self::$instance = new PDO('mysql:host=' . self::$hostname . ';dbname=' . self::$database, self::$username, self::$password, $opt);
            }catch(PDOException $e){
                die('<b>Error : </b>' . $e->getMessage());
            }
        }
        return self::$instance;
    }
}
