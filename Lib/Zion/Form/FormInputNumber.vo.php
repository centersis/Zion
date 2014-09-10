<?php
include_once './FormBasico.vo.php';

class FormInputNumberVo extends FormBasicoVo
{
    private $tipoBasico;
    private $tipo;    
    private $valorMaximo;
    private $valorMinimo;
    
    public function __construct($tipo)
    {
        $this->tipoBasico = 'number';
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
    
    public function setValorMaximo($valorMaximo)
    {
        $this->valorMaximo = $valorMaximo;
        return $this;
    }
    
    public function getValorMaximo()
    {
        return $this->valorMaximo;
    }
    
    public function setValorMinimo($valorMinimo)
    {
        $this->valorMinimo = $valorMinimo;
        return $this;
    }
    
    public function getValorMinimo()
    {
        return $this->valorMinimo;
    }
}