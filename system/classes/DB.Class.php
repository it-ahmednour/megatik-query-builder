<?php
require_once 'DBConn.CLass.php';

class DB{
    protected static $instance;
    protected static $conn;
    protected static $table;
    protected static $error;
    ############################
    //private $columns = '*';
    private $join;
    private $where;
    private $query;
    private $op = ['like', '=', '!=', '<', '>', '<=', '>=', '<>'];
    private $state = 'AND';
    private $not;

    ## function to set table && get instance of db connection Example = DB:table('users'); ##
    public static function table(string $table = null){
        self::$conn = DBConn::getInstance();
        if(!self::$instance){
            self::$instance = new self();
        }
        self::$table = self::setTable($table);
        return self::$instance;
    }

    ## Method To Insert Data In Table
    public function insert(Array $data){
        $keys = implode(',', array_keys($data));
        $values = implode(',', array_values( array_map( array($this, 'escape'),$data )));
        $this->query = sprintf("INSERT INTO %s (%s) VALUES (%s)", self::$table, $keys, $values);
        return $this;
    }

    ## Method For Update Data
    public function update(Array $data) {
        foreach ($data as $key => $value) {
            $columns[] = $key . " = " . $this->escape($value);
        }
        $columns = implode(" , ", $columns);
        $this->query = sprintf("UPDATE %s SET %s", self::$table, $columns);
        return $this;
    }

    ## Method To Select Delete From Table;
    public function delete(){
        $this->query = sprintf("DELETE FROM %s", self::$table);
        return $this;
    }

    /*
    ## select MIN functions => min('column');
    public function min($column, $name = null){
        $sql = "MIN($column)" . (!is_null($name) ? " AS {$name}" : '');
        $this->query = sprintf("SELECT %s FROM %s", $sql,self::$table);
        return $this;
    }

    ## select MAX functions => max('column');
    public function max($column, $name = null){
        $sql = "MAX($column)" . (!is_null($name) ? " AS {$name}" : '');
        $this->query = sprintf("SELECT %s FROM %s", $sql,self::$table);
        return $this;
    }

    ## select COUNT functions => count('column');
    public function count($column, $name = null){
        $sql = "COUNT($column)" . (!is_null($name) ? " AS {$name}" : '');
        $this->query = sprintf("SELECT %s FROM %s", $sql,self::$table);
        return $this;
    }

    ## select AVG functions => avg('column');
    public function avg($column, $name = null){
        $sql = "AVG($column)" . (!is_null($name) ? " AS {$name}" : '');
        $this->query = sprintf("SELECT %s FROM %s", $sql,self::$table);
        return $this;
    }

    ## select SUM functions => sum('column');
    public function sum($column, $name = null){
        $sql = "SUM($column)" . (!is_null($name) ? " AS {$name}" : '');
        $this->query = sprintf("SELECT %s FROM %s", $sql,self::$table);
        return $this;
    }
    */

    ## Method To Select Data From Table;
    public function select($columns = '*'){
        $columns = is_array($columns) ? implode(', ', $columns) : $columns;
        $this->query = sprintf("SELECT %s FROM %s", $columns, self::$table);
        return $this;
    }

    ## Method To Set Where
    public function where($where, $op = 'AND'){
        $where = (is_array($where) ? implode(" $op ", $where) : $where);
        $this->where = $where;
        return $this;
    }

    public function join($table, $key = null, $op = '', $foreign = null, $sql = ''){
        $setForeign = !is_null($foreign) ? $foreign : $op;
        $setOp = !is_null($foreign) ? $op : '=';
        $key = strpos(self::$table, 'AS') ? $key : $key;
        $setForeign = strpos($table, 'AS') ? $setForeign : $setForeign;
        $this->join .= " JOIN {$table} ON {$key} {$setOp} {$setForeign}";
        return $this;
    }

    private function setExtract($sql = ''){
        $sql .= $this->join;
        $sql .= !empty($this->where) ? ' WHERE ' . $this->where : '';
        $this->reset();
        return $sql;
    }
    
    ## Method To Build SQL Query
    public function sql(){
        $sql = $this->query . $this->setExtract();
        return $sql;
    }

    ## Method For Run Query Insert & Update & Delete
    public function run(){
        $sql = $this->sql();
        try{
            $stmt = self::$conn->prepare($sql);
            $stmt->execute();
            return true;
        }catch(PDOException $e){
            self::$error = $e->getMessage();
            return false;
        }
    }

    public function getall(){
        $sql = $this->sql();
        try{
            $stmt = self::$conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetchall(PDO::FETCH_OBJ);
        }catch(PDOException $e){
            self::$error = $e->getMessage();
            return false;
        }
    }

    public function get(){
        $sql = $this->sql();
        try{
            $stmt = self::$conn->prepare($sql);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_OBJ);
        }catch(PDOException $e){
            self::$error = $e->getMessage();
            return false;
        }
    }

    ## Return Affected Rows Count
    public static function affectedRows(){
        self::$affectedRows = self::$conn->affectedRows();
        return self::$affectedRows;
    }

    ## Return Last Insert ID
    public static function lastInsertId(){
        self::$lastInsertID = self::$conn->lastInsertId();
        return self::$lastInsertID;
    }

    ## Return Error MSG
    public static function get_error(){
        return self::$error;
    }

    ## rest class Property
    private function reset(){
        $this->select = null;
        $this->join = null;
        $this->where = null;
        $this->query = null;
    }

    ## Secure Value For SQL Injection
    private function escape($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        $data = htmlentities($data);
        $data = strip_tags($data);
        $data = self::$conn->quote($data);
        if($data === "''"){
            $data = 'NULL';
        }
        return $data;
    }
    
    ## Set Table And Check IF Exist
    private function setTable($table){
        if(empty($table)){
            die('Error : Table Name Is Empty');
        }
        try{
            self::$conn->query("SELECT 1 FROM {$table} LIMIT 1");
            return $table;
        }catch(PDOException $e){
            die('Error : Table "'.$table.'" Not Exist On Database');
        }
    }
    ## destruct class
    public function __destruct(){
        $this->reset();
        self::$instance = null;
        self::$conn = null;
        self::$table = null;
    }

}