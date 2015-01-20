<?php
/*

    Sappiens Framework
    Copyright (C) 2014, BRA Consultoria

    Website do autor: www.braconsultoria.com.br/sappiens
    Email do autor: sappiens@braconsultoria.com.br

    Website do projeto, equipe e documentação: www.sappiens.com.br
   
    Este programa é software livre; você pode redistribuí-lo e/ou
    modificá-lo sob os termos da Licença Pública Geral GNU, conforme
    publicada pela Free Software Foundation, versão 2.

    Este programa é distribuído na expectativa de ser útil, mas SEM
    QUALQUER GARANTIA; sem mesmo a garantia implícita de
    COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM
    PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais
    detalhes.
 
    Você deve ter recebido uma cópia da Licença Pública Geral GNU
    junto com este programa; se não, escreva para a Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
    02111-1307, USA.

    Cópias da licença disponíveis em /Sappiens/_doc/licenca

*/

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
