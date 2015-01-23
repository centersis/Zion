<?php
/**
*
*    Sappiens Framework
*    Copyright (C) 2014, BRA Consultoria
*
*    Website do autor: www.braconsultoria.com.br/sappiens
*    Email do autor: sappiens@braconsultoria.com.br
*
*    Website do projeto, equipe e documentação: www.sappiens.com.br
*   
*    Este programa é software livre; você pode redistribuí-lo e/ou
*    modificá-lo sob os termos da Licença Pública Geral GNU, conforme
*    publicada pela Free Software Foundation, versão 2.
*
*    Este programa é distribuído na expectativa de ser útil, mas SEM
*    QUALQUER GARANTIA; sem mesmo a garantia implícita de
*    COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM
*    PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais
*    detalhes.
* 
*    Você deve ter recebido uma cópia da Licença Pública Geral GNU
*    junto com este programa; se não, escreva para a Free Software
*    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
*    02111-1307, USA.
*
*    Cópias da licença disponíveis em /Sappiens/_doc/licenca
*
*/

namespace Pixel\Form;

class FormJavaScript extends \Zion\Layout\JavaScript
{

    public static $instancia;
    
    private $src;
    private $load;
    private $functions;

    private function __construct()
    {
        $this->src = [];
        $this->load = [];
        $this->functions = [];
    }

    /**
     * 
     * @return FormJavaScript
     */
    public static function iniciar()
    {
        if (!isset(self::$instancia)) {
            self::$instancia = new FormJavaScript();
        }

        return self::$instancia;
    }
    
    public function setSrc($url)
    {
        $this->src[] = $url;
        return $this;
    }

    public function getSrc()
    {
        $buffer = '';

        foreach ($this->src as $url) {
            $buffer.= parent::srcJS($url) . "\n";
        }

        return $buffer;
    }

    public function setLoad($codigo)
    {
        $this->load[] = $codigo;
        return $this;
    }
    
    public function resetLoad()
    {
        $this->load = [];
    }
    
    public function getLoad($entreJs = false)
    {
        $buffer = '';

        if ($this->load) {            
            $buffer = parent::abreLoadJQuery() . implode("\n", $this->load) . parent::fechaLoadJQuery();
            
            if($entreJs === true){
                $buffer = parent::entreJS($buffer);
            }
        }

        return $buffer;
    }
    
    public function setFunctions($codigoFonte)
    {
        $this->functions[] = $codigoFonte;
        return $this;
    }
    
    public function getFunctions()
    {
        $buffer = '';

        if ($this->functions) {
            $buffer = parent::entreJS(implode("\n", $this->functions));
        }

        return $buffer;
    }
    
    public function sisCadastrar($codigoJS)
    {
        return 'function sisCadastrar(){ '.$codigoJS.' } ';
    }
    
    public function sisAlterar($codigoJS)
    {
        return 'function sisAlterar(){ '.$codigoJS.' } ';
    }    

}
