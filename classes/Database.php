<?php

class Database {

  private $host;
  private $dbname;
  private $username;
  private $pass;
  public $conn;
  private $config;

  private function __construct(){
    $file = $_SERVER['DOCUMENT_ROOT']. '/../config/web.json';
    $config = json_decode(file_get_contents($file), true);
    $this->config = $config;
    $this->dbname = $config['dbname'];
    $this->username = $config['username'];
    $this->pass = $config['pass'];
    $this->conn = mysqli_connect($this->host, $this->username, $this->pass, $this->dbname);
    if(!$this->conn){
      echo 'Error Connecting';
    }
  }

  public function fetch($query){
    if(!$this->conn){
      echo 'Fetch Error';
      return false;
    }
    $result = mysqli_query($this->conn, $query);
    $retVal = array();
    while($row = mysqli_fetch_assoc($result)){
      array_push($retVal, $row);
    }
    return $retVal;
  }

  public function query($query){
    if(!$this->conn){
      echo 'Fetch Error';
      return false;
    }
    $result = mysqli_query($this->conn, $query);
    if(!$result){
      echo mysqli_error($this->conn) ;
      return false;
    } else {
      return true;
    }
  }

  public static function getInstance(){
    static $instance;
    if($instance == null){
    $instance = new Database();
    }
    return $instance;
  }

  public function lastId(){
    if(!$this->conn){
      return false;
    } else {
      return $this->conn->insert_id;
    }
  }

  public function getKey(){
    return "test";
    //return $this->config['key'];
  }


}






 ?>
