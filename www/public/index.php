<?php
require_once __DIR__ . '/../vendor/autoload.php';

define('VIEW_PATH',__DIR__.'/../src/Views/');

use App\Classes\MysqlConnector;
use App\Classes\EventsFromFile;
use App\Classes\Router;
use App\Controllers\HomeController;

use Dotenv\Dotenv;

// Retrive env variable
$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();


//set Global Exception Handeler 
set_exception_handler(function (\Throwable $e) {
    print($e->getMessage());
});

$requestPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);


//DB Connection Init 
$mysql = MysqlConnector::getInstance();
$mysql->Connect($_ENV['DB_HOST'], $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD'], $_ENV['DB_NAME']);

// Instace  Routes  
$router = new Router();

//register Route 
$router->register('/', [App\Controllers\HomeController::class, 'index']);



$file =  new EventsFromFile($mysql);

//Load data from JSON file  and save it into DB.
$file->fetch(__DIR__ . '/../Events.json');

// Boot the  Router  
echo $router->parse($requestPath);
