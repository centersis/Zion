<?php

namespace Zion\Core;

class Controller
{

    protected $model;
    protected $view;

    public function __construct()
    {
        $this->model = new Model();
        $this->view = new View($this->model);
    }
    
    public function clicked()  
    {  
        $this->model->string = "Dados atualizados, obrigado ao MVC + PHP!";  
    }
}
