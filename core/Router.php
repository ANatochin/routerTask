<?php

namespace Core;
use App\Controller\Admin\Index;
use App\Controller\Home\Home;
use App\Controller\Error\Error404;


class Router {
    protected $space;
    protected $controller;
    protected $method;

    public function __construct(){
        $this->init();
    }

    public function run(){
        $className = 'App\Controller\\'.$this->space.ucfirst(($this->controller));
        if(class_exists($className)){
            $this->makeControllerObject($className);
        } else {
            $this ->getError();
        }


    }

    public function init(){
        $path = [];
        if (!empty($_SERVER['REDIRECT_URL'])) {
            $urlModified = ltrim($_SERVER['REDIRECT_URL'], '/');
            $path = explode('/', $urlModified);
        }
        $this->controller = (!empty($path[0])) ? $path[0] : 'Home';
        $this->method = (!empty($path[1])) ? $path[1] : 'Index';
        if($this->controller === 'Index'){
            $this->space = 'Admin\\';
        }
        if($this->controller === 'Home'){
            $this->space = 'Home\\';
        }
    }

    public function makeControllerObject($name){
        $classObject = new $name();
        $classMethod = $this->method;
        if (method_exists($classObject,$classMethod)){
            $classObject->$classMethod();
        } else {
            $this ->getError();
        }
    }

    public function getError(){
        $errorObj404 = new Error404();
        $errorObj404->index();
    }
}


