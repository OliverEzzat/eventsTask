<?php

namespace App\Classes;

use App\Classes\Interfaces\DbInterface;
use PDO;

class MysqlConnector implements DbInterface
{

  private static  $instance;
  public  PDO $connection;

  //Blocking access to __construct to make Singelton pattern 
  private function __construct() {}
  // Blocking cloning
  protected function __clone() {}

  // Blocking unserialization
  public function __wakeup() {}

  static function getInstance(): MysqlConnector
  {

    if (!self::$instance) {
      self::$instance = new self();
    }

    return self::$instance;
  }

  function Connect($host, $user, $pass, $dbName): void
  {
    // Database connection
    try {
      $this->connection =  new PDO("mysql:host={$host};dbname={$dbName}", $user, $pass);
      $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (\PDOException $e) {

      die("Database connection failed: " . $e->getMessage());
    }
  }
}
