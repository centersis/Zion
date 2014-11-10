<?php
/**
 * 
 * @author Feliphe Bueno - feliphezion@gmail.com
 * @since 6/11/2014
 * @version 1.0
 * @copyright 2014
 * 
 * 
 * 
 */

namespace Zion\Exportacao;

class PDFVO {
    
    private $mainTableStyle;
    private $colsWidth;
    private $colsHeight;
    private $urlLogo;
    private $logoAlignment;
    private $stylesheet;
    private $tituloRelatorio;
    private $descricaoRelatorio;
    private $orientacaoRelatorio;

    public function getMainTableStyle(){
        return $this->mainTableStyle;
    }

    public function setMainTableStyle($mainTableStyle){
        $this->mainTableStyle = $mainTableStyle;
    }

    public function getColsWidth(){
        return $this->colsWidth;
    }

    public function setColsWidth($colsWidth){
        $this->colsWidth = $colsWidth;
    }

    public function getColsHeight(){
        return $this->colsHeight;
    }

    public function setColsHeight($colsHeight){
        $this->colsHeight = $colsHeight;
    }

    public function getUrlLogo(){
        return $this->urlLogo;
    }

    public function setUrlLogo($urlLogo){
        $this->urlLogo = $urlLogo;
    }

    public function getLogoAlignment(){
        return $this->logoAlignment;
    }

    public function setLogoAlignment($logoAlignment){
        $this->logoAlignment = $logoAlignment;
    }

    public function getStylesheet(){
        return $this->stylesheet;
    }

    public function setStylesheet($stylesheet){
        $this->stylesheet = $stylesheet;
    }

    public function getTituloRelatorio(){
        return $this->tituloRelatorio;
    }

    public function setTituloRelatorio($tituloRelatorio){
        $this->tituloRelatorio = $tituloRelatorio;
    }

    public function getDescricaoRelatorio(){
        return $this->descricaoRelatorio;
    }

    public function setDescricaoRelatorio($descricaoRelatorio){
        $this->descricaoRelatorio = $descricaoRelatorio;
    }

    public function getOrientacaoRelatorio(){
        return $this->orientacaoRelatorio;
    }

    public function setOrientacaoRelatorio($orientacaoRelatorio){
        $this->orientacaoRelatorio = $orientacaoRelatorio;
    }    
    
}
