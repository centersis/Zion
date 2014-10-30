<?php
/**
 * 
 * @author Feliphe Bueno - feliphezion@gmail.com
 * @since 28/10/2014
 * @version 1.0
 * @copyright 2014
 * 
 * 
 * 
 */

namespace Pixel\Paginacao;

class Paginacao extends \Zion\Paginacao\Paginacao
{

    public function __construct($con = NULL){

        if (!$con) {
            $con = \Zion\Banco\Conexao::conectar();
        }

        parent::__construct($con);
    }
    
    
    public function listaResultados(){

        self::setPaginacaoPixelTemplate();
        return parent::listaResultados();

    }
    
    public function setPaginacaoPixelTemplate(){
        
        parent::setDivDrop('btn-toolbar pull-right recI10px'); //Div 
        parent::setDivDropGroup('btn-group');
        parent::setDivDropGroupItems('btn-group');
        parent::setDivRols('table-footer');
        parent::setDivFpOff('table-footer');
        parent::setDivPagOff('btn-toolbar pull-right');
        parent::setIDrop('fa fa-list-ol');
        parent::setIDropCaret('fa fa-caret-down');
        parent::setIRew('fa fa-chevron-left');
        parent::setIFwd('fa fa-chevron-right');
        parent::setIFp('dropdown-icon fa fa-angle-double-left');
        parent::setILp('dropdown-icon fa fa-angle-double-right');
        parent::setUlDrop('dropdown-menu');
        parent::setLiFp('hand mm-text');
        parent::setLiLp('hand');
        parent::setSpanRols('label label-warning');
        parent::setSpanDropPags('mm-text');
        parent::setButtonDrop('btn btn-lg dropdown-toggle');
        parent::setButtonRew('btn btn-lg');
        parent::setButtonFwd('btn btn-lg');
        parent::setButtonRewOff('btn btn-lg disabled');
        parent::setButtonFwdOff('btn btn-lg disabled');
        
        return $this;

    }

}
