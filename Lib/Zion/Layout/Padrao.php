<?php
 
namespace Zion\Layout;

class Padrao extends \Zion\Layout\Html
{
    /**
     * @var mixed $html
     */
    protected $html;
    
    /**
     * @var mixed $javascript
     */
    private $javascript;

    /**
     * Padrao::__construct()
     * 
     * @return
     */
    public function __construct()
    {
        $this->html = new \Zion\Layout\Html();
        $this->javascript = new \Zion\Layout\JavaScript();
    }


    /**
     * Padrao::topo()
     * 
     * @return
     */
    public function topo()
    {

        $buffer  = '';        
        $buffer .= $this->html->abreTagAberta('!DOCTYPE html');
        $buffer .= "<!--[if IE 8]>\n<html class=\"ie8\" lang=\"pt-BR\">\n<![endif]-->";
        $buffer .= "<!--[if IE 9]>\n<html class=\"ie9 gt-ie8\" lang=\"pt-BR\">\n<![endif]-->\n";
        $buffer .= "<!--[if gt IE 9]><!-->\n<html class=\"gt-ie8 gt-ie9 not-ie\" lang=\"pt-BR\">\n<!--<![endif]-->\n";
        //$buffer .= $this->html->abreTagAberta('html', array('lang'=>'pt-BR'));
        $buffer .= $this->html->abreTagAberta('head');
        $buffer .= $this->html->abreTagAberta('meta', array('charset'=>'utf-8'));
        $buffer .= $this->html->abreTagAberta('meta', array('http-equiv'=>'X-UA-Compatible','content'=>'IE=edge,chrome=1'));
        $buffer .= $this->html->entreTags('title', 'Sappiens Framework');
        $buffer .= $this->html->abreTagAberta('meta', array('name'=>'generator','content'=>"Sappiens Framework"));
        $buffer .= $this->html->abreTagAberta('meta', array('name'=>'manifest','content'=>"Tah olhando o codigo-fonte? Vem trabalhar com a gente! [curriculos@braconsultoria.com.br]"));
        $buffer .= $this->html->abreTagAberta('meta', array('name'=>'description','content'=>SIS_NOME_PROJETO));
        $buffer .= $this->html->abreTagAberta('meta', array('name'=>'author','content'=>SIS_AUTOR));
        $buffer .= $this->html->abreTagAberta('meta', array('name'=>'release','content'=>SIS_RELEASE));
        $buffer .= $this->html->abreTagAberta('meta', array('name'=>'viewport','content'=>"width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no"));                                        
        $buffer .= $this->html->abreTagAberta('link', array('rel'=>'canonical','href'=> 'http://localhost/' . $_SERVER['REQUEST_URI']));           
        
        return $buffer;
    }
    
    /**
     * Padrao::menu()
     * 
     * @return
     */
    public function menu()
    {
        
    }
    
    /**
     * Padrao::rodape()
     * 
     * @return
     */
    public function rodape()
    {

        $buffer  = '';
        $buffer .= $this->html->fechaTag('body');
        $buffer .= $this->html->fechaTag('html');

        return $buffer;
    }
}
