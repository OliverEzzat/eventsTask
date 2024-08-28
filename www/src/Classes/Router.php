<?php

namespace App\Classes;

use App\Exceptions\RouteNotFoundException;

class Router
{

  private array $routes;

  // Register Routes 
  public function register(string $route, callable|array $action): Router
  {

    $this->routes[$route] = $action;

    return $this;
  }


  // this will parse the URL and resolve the path registerd in Routes 
  public function parse(string $request)
  {

    $action = $this->routes[$request] ?? null;

    if (!$action)
      throw new  RouteNotFoundException();

    if (is_callable($action))
      return  call_user_func($action);

    [$class, $method] = $action;
    if (class_exists($class)) {

      $class = new $class();

      if (method_exists($class, $method))
        return call_user_func_array([$class, $method], []);
    }

    throw new  RouteNotFoundException();
  }
}
