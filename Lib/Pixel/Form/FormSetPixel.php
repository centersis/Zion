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

namespace Pixel\Form;
use \Zion\Form\Exception\FormException as FormException;

class FormSetPixel
{  
    
    public function setIconFA($iconFA)
    {
        if (!empty($iconFA)) {
            return $iconFA;
        } else {
            throw new FormException("iconFA: Nenhum valor informado");
        }
    }
    
    public function setToolTipMsg($toolTipMsg)
    {
        if (!empty($toolTipMsg)) {
            return $toolTipMsg;
        } else {
            throw new FormException("toolTipMsg: Nenhum valor informado");
        }
    }
   
    public function setEmColunaDeTamanho($emColunaDeTamanho)
    {        
        if (in_array($emColunaDeTamanho, range(1, 12))) {
            return $emColunaDeTamanho;
        } else {
            throw new FormException("emColunaDeTamanho: Use variação de 1 a 12");
        }
    }
    
    public function setMascara($mascara)
    {
        if (!empty($mascara)) {
            return $mascara;
        } else {
            throw new FormException("mascara: Nenhum valor informado");
        }
    }
    
    public function setLayoutPixel($layoutPixel)
    {
       if (is_bool($layoutPixel)) {            
            return $layoutPixel;
        } else {
            throw new FormException("layoutPixel: Valor nao booleano");
        }
    }

    public function setLabelAntes($labelAntes)
    {
        return $labelAntes;
    }

    public function setProcessarJS($processarJS)
    {
        return $processarJS;
    }
    
    public function setTipoFiltro($tipoFiltro)
    {
        return $tipoFiltro;
    }

    public function setLabelDepois($labelDepois)
    {
        return $labelDepois;
    }    
}