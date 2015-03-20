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

namespace Zion\Log;

class Log extends LogSql
{
    protected $con;
    
    public function __construct() 
    {
        $this->con = \Zion\Banco\Conexao::conectar();       
    }
    
    public function registraLog(\Doctrine\DBAL\Query\QueryBuilder $sql, $logHash)
    {
        $sqlCompleta    = $this->getSqlCompleta($sql);
        $actParams      = $this->getActionParams();
        $this->salvarlLog($actParams, $sqlCompleta, $logHash);
    }
    
    private function getActionParams()
    {
        $modulo = $this->getDadosModulo(MODULO);
        $id     = \filter_input(\INPUT_POST, 'cod');
        $tab    = \filter_input(\INPUT_POST, 'n');
        $acao   = \filter_input(\INPUT_GET, 'acao');
              
        return ['usuarioCod'    => $_SESSION['usuarioCod'],
                'modulo'        => $modulo, 
                'id'            => $id, 
                'acao'          => $acao,
                'tab'           => $tab
               ];

    }
      
    private function getSqlCompleta(\Doctrine\DBAL\Query\QueryBuilder $sql)
    {
        $params     = $sql->getParameters();
        $types      = $sql->getParameterTypes();


        $paramTypes = $params;
        $paramTypes = \array_map(function($param) {
                        return (\is_numeric($param) ? 1 : 2);
                      }, $paramTypes);

        
        $sqlCompleta = $sql->getSQL();
        $i = 0;

        foreach($paramTypes as $param => $type){

            $replacement = ($type == 1 ? $params[$param] : "'". $params[$param] ."'");
            $sqlCompleta = preg_replace(['/:'. $param .'/', '/\?/'], $replacement, $sqlCompleta, 1);
        }
        
        return $sqlCompleta;
    }
    
    protected function getDadosModulo($moduloNome)
    {
        return $this->con->execLinha(parent::getDadosModuloSql($moduloNome));
    }
    
    protected function salvarlLog($actParams, $sqlCompleta, $logHash)
    {
        parent::salvarLogSql($actParams, $sqlCompleta, $logHash)->execute();
    }
}
