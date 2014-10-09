<?php

namespace Lib\Pixel\Form;

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
    
    public function getLoad()
    {
        $buffer = '';

        if ($this->load) {
            $buffer = parent::abreLoadJQuery() . implode("\n", $this->load) . parent::fechaLoadJQuery();
        }

        return $buffer;
    }
    
    public function setFunctions($codigo)
    {
        $this->functions[] = $codigo;
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

}
