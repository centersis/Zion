<?php

include_once './FormInputTexto.vo.php';
include_once './FormInputButton.vo.php';

class Form
{
    private $formValues;
    private $processarHtml;
    private $processarJs;
    
    public function __construct()
    {
        $this->formValues    = array();
        $this->processarHtml = true;
        $this->processarJs   = true;
    }
    
    public function texto()
    {
        return new FormInputTextoVo('texto');        
    }
    
    public function senha()
    {
        return new FormInputTextoVo('password');        
    }
    
    public function mail()
    {
        return new FormInputTextoVo('email');        
    }
    
    public function inteiro()
    {
        return new FormInputTextoVo('inteiro');        
    }
    
    public function float()
    {
        return new FormInputTextoVo('moeda');        
    }
    
    public function botaoSimples()
    {
        return new FormInputButtonVo('button');
    }
    
    public function botaoSubmit()
    {
        return new FormInputButtonVo('bubmit');
    }
    
    public function botaoReset()
    {
        return new FormInputButtonVo('reset');
    }
 
    public function processarForm(array $campos)
    {
        $htmlCampos = array();
        
        foreach ($campos as $objCampos){
            
            if($this->processarHtml){
                switch ($objCampos->getTipoBasico()){
                    case 'texto' : $htmlCampos[$objCampos->getNome()] = $this->montaInput($objCampos); break;
                    case 'button': $htmlCampos[$objCampos->getNome()] = $this->montaButton($objCampos); break;
                }                
            }           
        }
        
        if($this->processarHtml){
            return $htmlCampos;
        }
    }
    
    private function montaInput(FormInputTextoVo $config)
    {
        if(empty($config->getNome())){
            throw new Exception('Atributo nome é obrigatório');
        }
        
        $name        = 'name="'.$config->getNome().'"';        
        $id          = ($config->getId() == '') ? 'id="'.$config->getNome().'" ' : 'id="'.$config->getId().'"';
        $tipo        = 'type="'.strtolower($config->getTipo()).'"';
        $value       = ' value="'.$config->getValor().'" ';
        $size        = ($config->getLargura()) ? 'size="'.$config->getLargura().'"' : '';
        $len         = (is_numeric($config->getMaximoCaracteres())) ? 'maxlength="'.$config->getMaximoCaracteres().'"' : '';
        $complemento = $config->getComplemento();
        $disable     = ($config->getDisabled() === false) ? 'disabled="disabled"' : '';
        
        if ($config->getMaiusculoMinusculo() == "ALTA") {
            $estiloCaixa = 'style="text-transform: uppercase;"';
        } else if ($config->getMaiusculoMinusculo() == "BAIXA") {
            $estiloCaixa = 'style="text-transform: lowercase;"';
        }
        else{
            $estiloCaixa = '';
        }

        $retorno = sprintf("<input %s %s %s %s %s %s %s %s %s/>", $name, $id, $tipo, $value, $size, $len, $estiloCaixa, $complemento, $disable);

        return $retorno;
    }
    
    private function montaButton(FormInputButtonVo $config)
    {
        if(empty($config->getNome())){
            throw new Exception('Atributo nome é obrigatório');
        }
        
        $name        = $config->getNome();        
        $id          = ($config->getId() == '') ? 'id="'.$config->getNome().'" ' : 'id="'.$config->getId().'"';
        $tipo        = 'type="'.strtolower($config->getTipo()).'"';
        $value       = ' value="'.$config->getValor().'" ';
        $complemento = $config->getComplemento();
        $disable     = ($config->getDisabled() === false) ? 'disabled="disabled"' : '';

        $retorno = sprintf("<button %s %s %s %s %s>%s</button>", $name, $id, $tipo, $complemento, $disable, $value);

        return $retorno;
    }
    
    public function set($nome, $valor)
    {
        $this->formValues[$nome] = $valor;
    }
    
    public function get($nome)
    {
        return $this->formValues[$nome];
    }
    
    public function setProcessarHtml($processarHtml)
    {
        $this->processarHtml = $processarHtml;
    }
    
    public function setProcessarJs($processarJs)
    {
        $this->processarJs = $processarJs;
    }
}
