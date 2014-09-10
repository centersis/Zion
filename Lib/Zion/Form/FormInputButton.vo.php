<?php
include_once './FormBasico.vo.php';

class FormInputButtonVo extends FormBasicoVo
{
    private $tipoBasico;
    private $tipo;
    private $metodo;
    private $action;
    private $target;
    
    public function __construct($tipo)
    {
        $this->tipoBasico = 'button';
        $this->tipo = $tipo;
    }
    
    public function getTipoBasico()
    {
        return $this->tipoBasico;
    }
    
    public function getTipo()
    {
        return $this->tipo;
    }
    
    public function setMetodo($metodo)
    {
        $this->metodo = $metodo;
        return $this;
    }
    
    public function getMetodo()
    {
        return $this->metodo;
    }
    
    public function setAction($action)
    {
        $this->action = $action;
        return $this;
    }
    
    public function getAction()
    {
        return $this->action;
    }
    
    public function setTarget($target)
    {
        $this->target = $target;
        return $this;
    }
    
    public function getTarget()
    {
        return $this->target;
    }
}