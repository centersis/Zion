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
    
    /**
     * Registro de LOG segundo a perspectiva humana
     * 
     * 
     */
    public function registraLogUsuario($usuarioCod, $moduloNome, $logAcao, \Doctrine\DBAL\Query\QueryBuilder $logSql, $logId = NULL, $logHash = false, $logTab = NULL)
    {
        $this->salvarlLog(['usuarioCod' => $usuarioCod,
                            'moduloCod' => $this->getDadosModulo($moduloNome)['modulocod'], 
                            'id'        => $logId, 
                            'acao'      => $logAcao,
                            'tab'       => $logTab
                           ], $this->getSqlCompleta($logSql), ($logHash ?: \bin2hex(\openssl_random_pseudo_bytes(25))));
    }
    
    public function registraLog(\Doctrine\DBAL\Query\QueryBuilder $sql, $logHash)
    {
        $sqlCompleta    = $this->getSqlCompleta($sql);
        $actParams      = $this->getActionParams();

        if(empty($actParams['id'])){

            if($sql->getType() === 1 or $sql->getType() === 2){
                $actParams['id'] = $this->getIdRegistroSql($sql, $sqlCompleta);
            } elseif($sql->getType() === 3) {
                $actParams['id'] = NULL;
            }
        }
        
        $this->salvarlLog($actParams, $sqlCompleta, $logHash);
    }
    
    private function getActionParams()
    {
        $modulo = $this->getDadosModulo(MODULO);
        $id     = \filter_input(\INPUT_POST, 'cod');
        $tab    = \filter_input(\INPUT_POST, 'n');
        $acao   = \filter_input(\INPUT_GET, 'acao');
              
        return ['usuarioCod'    => $_SESSION['usuarioCod'],
                'moduloCod'     => $modulo['modulocod'], 
                'id'            => $id, 
                'acao'          => $acao,
                'tab'           => $tab
               ];

    }
    
    private function getIdRegistroSql(\Doctrine\DBAL\Query\QueryBuilder $sql, $sqlCompleta)
    {
        $parts = $this->getAtributosPrivados($sql->getQueryPart('where'));
        
        if(!isset($parts['parts'])){
            return NULL;
        }
        
        $idRegistro = NULL;

        foreach($parts['parts'] as $k => $v) {
            
            $param = \preg_replace('/\s/', '', \explode('=', $v)[0]);
            $matches = [];
            \preg_match('/'. $param .'\s=\s[0-9]{1,}/', $sqlCompleta, $matches);
            
            $valParam = (int) \explode('=', $matches[0])[1];
            
            if($valParam > 0){
                $idRegistro = $valParam;
                break;
            }
        }
        
        return $idRegistro;
    }
    
    private function getAtributosPrivados($input)
    {
        $attrs = array();
        foreach (((array) $input) as $key => $val) {

            $key = preg_replace(array('/'. \addslashes('Doctrine\DBAL\Query\Expression\CompositeExpression') .'/', '/\W/'), '', $key);
            $attrs[$key] = $val;
        }

        return $attrs;
    }

    private function getSqlCompleta(\Doctrine\DBAL\Query\QueryBuilder $sql)
    {
        $params     = $sql->getParameters();

        $paramTypes = \array_map(function($param) {
                        return (\is_numeric($param) ? 1 : 2);
                      }, $params);

        
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
