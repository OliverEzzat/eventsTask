<?php

namespace App\Classes;


class View
{

    public function __construct(protected string $path, protected array $prams = []) {}


    public function render()
    {
        ob_start();
      
        return  include VIEW_PATH . $this->path . '.php';

        ob_clean();
    }
}
