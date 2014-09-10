<?php
include_once './FormBasico.vo.php';

class FormInputDateVo extends FormBasicoVo
{
    private $tipoBasico;
    private $tipo;    
    private $dataMinima;
    private $dataMaxima;
    
    public function __construct($tipo)
    {
        $this->tipoBasico = 'date';
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
    
    public function setDataMinima($dataMinima)
    {
        $this->dataMinima = $dataMinima;
        return $this;
    }
    
    public function getDataMinima()
    {
        return $this->dataMinima;
    }
    
    public function setDataMaxima($dataMaxima)
    {
        $this->dataMaxima = $dataMaxima;
        return $this;
    }
    
    public function getDataMaxima()
    {
        return $this->dataMaxima;
    }
}