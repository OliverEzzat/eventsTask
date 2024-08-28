<?php 
namespace App\Classes\Interfaces;


interface DbInterface
{
 public function Connect($host, $user, $pass, $dbName);
}
