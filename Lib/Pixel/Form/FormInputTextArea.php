<?php

namespace Pixel\Form;

use Pixel\Form\FormSetPixel;
use Zion\Form\FormInputTextArea as FormInputTextAreaZion;

class FormInputTextArea extends FormInputTextAreaZion
{

    private $ferramentas;
    private $iconFA;
    private $toolTipMsg;
    private $emColunaDeTamanho;
    private $offsetColuna;
    private $processarJS;
    private $complementoExterno;
    private $hashAjuda;
    private $formSetPixel;

    public function __construct($acao, $nome, $identifica, $obrigatorio)
    {
        parent::__construct($acao, $nome, $identifica, $obrigatorio);
        $this->formSetPixel = new FormSetPixel();
    }

    public function setFerramentas($ferramentas)
    {
        $this->ferramentas = $ferramentas;

        return $this;
    }

    public function getFerramentas()
    {
        switch (strtoupper($this->ferramentas)) {

            case 'PADRAO': case 'COMPLETO': case 'COMPLETA':
                return "{
                    toolbar: [                    
                    { name: 'basicstyles', items: 
                        ['NewPage','-','Undo','Redo','-','Bold', 'Italic', 'Strike', '-', 'RemoveFormat' ],
                    },
                        ['JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'],
                        [ 'TextColor', 'BGColor' ],
                        [ 'FontSize','-','Font' ],
                        ['base64image','-','Link', 'Unlink'],
                        ['NumberedList', 'BulletedList'], 
                        ['HorizontalRule', '-', 'Blockquote','-','Table', 'PasteText', 'PasteFromWord'],                                                                                             
                        ['-','Source','-','Maximize' ]
                    ]
                    }";

            case 'BASICA': 

                return "{
                    toolbar: [                    
                    { name: 'basicstyles', items: 
                        ['NewPage','-','Undo','Redo','-','Bold', 'Italic', 'Strike', '-', 'RemoveFormat' ],
                    },
                        ['JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'],
                        ['base64image','-','Link', 'Unlink'],
                        ['-','Source','-','Maximize' ]
                    ]
                    }";

            case 'EMAIL': 

                return "{
                    toolbar: [                    
                    { name: 'basicstyles', items: 
                        ['NewPage','-','Undo','Redo','-','Bold', 'Italic', 'Strike', '-', 'RemoveFormat' ],
                    },
                        ['JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'],
                        [ 'TextColor', 'BGColor' ],
                        [ 'FontSize','-','Font' ],
                        ['Image','-','Link', 'Unlink'],
                        ['NumberedList', 'BulletedList'], 
                        ['HorizontalRule', '-', 'Blockquote','-','Table', 'PasteText', 'PasteFromWord'],                                                                                             
                        ['-','Source','-','Maximize' ]
                    ]
                    }";

            default : return null;
        }
    }

    public function setMaximoCaracteres($maximoCaracteres)
    {
        parent::setMaximoCaracteres($maximoCaracteres);
        return $this;
    }

    public function setMinimoCaracteres($minimoCaracteres)
    {
        parent::setMinimoCaracteres($minimoCaracteres);
        return $this;
    }

    public function setObrigatorio($obrigatorio)
    {
        parent::setObrigatorio($obrigatorio);
        return $this;
    }

    public function setPlaceHolder($placeHolder)
    {
        parent::setPlaceHolder($placeHolder);
        return $this;
    }

    public function setAliasSql($aliasSql)
    {
        parent::setAliasSql($aliasSql);
        return $this;
    }

    public function setReadonly($readonly)
    {
        parent::setReadonly($readonly);
        return $this;
    }

    public function setColunas($colunas)
    {
        parent::setColunas($colunas);
        return $this;
    }

    public function setLinhas($linhas)
    {
        parent::setLinhas($linhas);
        return $this;
    }

    public function setForm($form)
    {
        parent::setForm($form);
        return $this;
    }

    public function setIconFA($iconFA)
    {
        $this->iconFA = $this->formSetPixel->setIconFA($iconFA);
        return $this;
    }

    public function getIconFA()
    {
        return $this->iconFA;
    }

    public function setToolTipMsg($toolTipMsg)
    {
        $this->toolTipMsg = $this->formSetPixel->setToolTipMsg($toolTipMsg);
        return $this;
    }

    public function getToolTipMsg()
    {
        return $this->toolTipMsg;
    }

    public function setEmColunaDeTamanho($emColunaDeTamanho)
    {
        $this->emColunaDeTamanho = $this->formSetPixel->setEmColunaDeTamanho($emColunaDeTamanho);
        return $this;
    }

    public function getemColunaDeTamanho()
    {
        return $this->emColunaDeTamanho ? $this->emColunaDeTamanho : 12;
    }

    public function setOffsetColuna($offsetColuna)
    {
        $this->offsetColuna = $this->formSetPixel->setOffsetColuna($offsetColuna);
        return $this;
    }

    public function getOffsetColuna()
    {
        return $this->offsetColuna ? $this->offsetColuna : 3;
    }

    public function setProcessarJS($processarJS)
    {
        $this->processarJS = $this->formSetPixel->setProcessarJS($processarJS);
        return $this;
    }

    public function getProcessarJS()
    {
        return $this->processarJS;
    }

    public function setComplementoExterno($complementoExterno)
    {
        $this->complementoExterno = $this->formSetPixel->setComplementoExterno($complementoExterno);
        return $this;
    }

    public function getComplementoExterno()
    {
        return $this->complementoExterno;
    }

    public function setHashAjuda($hashAjuda)
    {
        $this->hashAjuda = $this->formSetPixel->setHashAjuda($hashAjuda);
        return $this;
    }

    public function getHashAjuda()
    {
        return $this->hashAjuda;
    }

    /**
     * Sobrecarga de Metodos Básicos
     */
    public function setId($id)
    {
        parent::setId($id);
        return $this;
    }

    public function setNome($nome)
    {
        parent::setNome($nome);
        return $this;
    }

    public function setIdentifica($identifica)
    {
        parent::setIdentifica($identifica);
        return $this;
    }

    public function setValor($valor)
    {
        parent::setValor($valor);
        return $this;
    }

    public function setValorPadrao($valorPadrao)
    {
        parent::setValorPadrao($valorPadrao);
        return $this;
    }

    public function setDisabled($disabled)
    {
        parent::setDisabled($disabled);
        return $this;
    }

    public function setComplemento($complemento)
    {
        parent::setComplemento($complemento);
        return $this;
    }

    public function setAtributos($atributos)
    {
        parent::setAtributos($atributos);
        return $this;
    }

    public function setClassCss($classCss)
    {
        parent::setClassCss($classCss);
        return $this;
    }

    public function setContainer($container)
    {
        parent::setContainer($container);
        return $this;
    }

}
