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
    
    /*
    public function listaResultados(){
        print parent::listaResultados();exit("HEREDOC");
    }
    */
}
