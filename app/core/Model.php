<?php

class Model {

  private static $host = "127.0.0.1";
  private static $database = "blog";
  private static $user = "giovanne";
  private static $password = "";
  private static $connection = null;
  
  private static function Connect()
  {

    self::$connection = new PDO("mysql:host=" . self::$host . ";dbname=" . self::$database , self::$user , self::$password );

    return self::$connection;
    
  }

  public function getConnection()
  {
    return self::Connect();
  }
  
}