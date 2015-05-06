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

/**
 * 
 * @author Feliphe Bueno - feliphezion@gmail.com
 * @since 9/1/2015
 * @version 1.0
 * @copyright 2015
 * 
 * 
 * 
 */

namespace Pixel\Grid;

class Impressao
{
    
    private $logo;

    public function __construct()
    {
        
    }

    public function imprimePDF($html)
    {
        $pdf = new \Zion\Exportacao\PDF();

        $orientacao = ($this->getNumeroColunas($html) > 5 ? 'L' : 'P');

        if($pdf->imprimePDF($this->trataHTML($html), NULL, $orientacao) === false){
            return false;
        } else {
            return true;
        }
    }

    public function imprimeHTML($html)
    {
        $css = '<style>
                th {
                    font-family: Verdana, Arial, Helvetica, sans-serif;
                    background-color: #666666;
                    color:#FFFFFF;
                    font-size: 13px;
                    height:30px;
                }
    			tbody{
    				margin-top:20px;
    				border:1px solid #666666;
    				border-bottom: none;
    			}
    			.table-bordered {
    				margin-bottom:20px;
                    width: 100%;
    			 }
                .table-bordered tr{
                    border:1px solid #666666;
    			}
                td {
                    border:1px solid #666666;
                    font-family: Verdana, Arial, Helvetica, sans-serif;
                    font-size: 12px;
    				text-align:center;
    				height:25px;
                }
                .t12preto {
                    font-family: Verdana, Arial, Helvetica, sans-serif;            
                    font-size: 12px;
                    color: #000000;            
                    text-decoration: none;
                    border:none;
                }
                .table-footer {
                    font-family: Verdana, Arial, Helvetica, sans-serif;
                    font-size: 12px;
                    text-decoration: none;
                }
                </style><body onload="">';
        return $css . $this->trataHTML($html) . '</body>';
    }

    public function trataHTML($html)
    {
        preg_match_all('/<td class="l45px" >/', $html, $matches);

        //Remove a coluna dos checkboxes, no futuro haverá uma solução mais elegante. =D
        $i = 0;
        while($i <= count($matches[0])){

            $i++;

            $abreTd     = strpos($html, '<td class="l45px"');
            $fechaTd    = strpos($html, '</td>', $abreTd) + 5;

            if($abreTd) {
                $start      = substr($html, 0, $abreTd);
                $end        = substr($html, $fechaTd);
                $html       = $start . $end;
                continue;
            } else {
                break;
            }
        }

        $html = preg_replace('|<th ></th>|', '', $html);

        return $this->montaCabecalho() . $html;
    }
    
    public function montaCabecalho()
    {
        $html = '
            <table width="100%">
                <tr>
                    <td class="t12preto" colspan="2"><div align="left"><img src="'. $this->getLogo() .'"/></div></td>
                </tr>
                <tr>
                    <td colspan="2" height="15" class="t12preto" align="center">&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="2" height="25" class="t12preto" align="center"><strong>Impressão dos registros do módulo '. ucfirst(MODULO) .'.</strong></td>
                </tr>
                <tr>
                    <td colspan="2" height="10" class="t12preto" align="center">&nbsp;</td>
                </tr>
              </table>';

        return $html;
    }
    
    public function setLogo($logo)
    {
        if(!empty($logo)){
            $this->logo = $logo;
            return $this;
        } else {
            throw new \Exception("A URL informada não é de uma logo válida.");
        }
    }
    
    public function getLogo()
    {
        return $this->logo;
    }
    
    public function getNumeroColunas($html)
    {
        preg_match_all('/<i class="fa fa-sort recD5px" >/', $html, $matches);

        if(is_array($matches[0])){
            return count($matches[0]);
        } else {
            return 5;
        }

    }
}