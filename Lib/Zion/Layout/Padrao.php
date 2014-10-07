<?php

namespace Zion\Layout;

class Padrao extends \Zion\Layout\Html
{
    protected $html;
    private $css;
    private $javascript;
    
    public function __construct()
    {
        $this->html = new \Zion\Layout\Html();
        $this->css = new \Zion\Layout\Css();
        $this->javascript = new \Zion\Layout\JavaScript();
    }


    public function topo()
    {

        $buffer  = false;        
        $buffer .= $this->html->abreTagAberta('!DOCTYPE html');
        $buffer .= $this->html->abreTagAberta('html', array('lang'=>'pt-BR'));
        $buffer .= $this->html->abreTagAberta('head');
        $buffer .= $this->html->abreTagAberta('meta', array('charset'=>'utf-8'));
        $this->html->abreTagAberta('meta', array('http-equiv'=>'X-UA-Compatible','content'=>'IE=edge,chrome=1')).
        $buffer .= $this->html->entreTags('title', ' ' . DEFAULT_MODULO_NOME . " - " . CFG_SIS_NOME . ' ');

        $buffer .= $this->html->abreTagAberta('meta', array('name'=>'generator','content'=>"Zion Framework"));
        $buffer .= $this->html->abreTagAberta('meta', array('name'=>'manifest','content'=>"Tah olhando o código-fonte? Vem trabalhar com a gente! [curriculos@braconsultoria.com.br]"));
        $buffer .= $this->html->abreTagAberta('meta', array('name'=>'description','content'=>CFG_SIS_DESCRICAO));
        $buffer .= $this->html->abreTagAberta('meta', array('name'=>'author','content'=>CFG_SIS_AUTOR));
        $buffer .= $this->html->abreTagAberta('meta', array('name'=>'release','content'=>CFG_SIS_RELEASE));
        $buffer .= $this->html->abreTagAberta('meta', array('name'=>'viewport','content'=>"width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"));                                        
        $buffer .= $this->css->abreTagAberta('link', array('rel'=>'canonical','href'=> URL_BASE_DEFAULT . $_SERVER['REQUEST_URI']));           
        
        return $buffer;
    }
    
    public function menu()
    {
        
    }
    
    public function rodape()
    {

        $buffer  = false;
        $buffer .= $this->html->fechaTag('body');
        $buffer .= $this->html->fechaTag('html');

        return $buffer;
    }
}
