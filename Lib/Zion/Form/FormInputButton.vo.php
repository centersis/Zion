<?php
include_once './FormBasico.vo.php';

class FormInputButtonVo extends FormBasicoVo
{
    private $tipoBasico;
    private $tipo;
    
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
}