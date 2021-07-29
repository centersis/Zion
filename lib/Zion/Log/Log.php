<?php

namespace Zion\Log;

use Zion\Banco\Conexao;

class Log extends LogSql
{

    /**
     * 
     * @param int $usuario_cod
     * @param string $moduloNome
     * @param string $logAcao
     * @param \Doctrine\DBAL\Query\QueryBuilder $logSql
     * @param int $logId
     * @param string $logHash
     * @param string $logTab
     */
    public function registraLogUsuario($usuario_cod, $moduloNome, $logAcao, $logSql, $logId = null, $logHash = false)
    {
        $this->salvarlLog(['usuario_cod' => $usuario_cod,
            'modulo_cod' => $this->getDadosModulo($moduloNome)['modulo_cod'],
            'id' => $logId,
            'acao' => $logAcao,
            ], $this->getSqlCompleta($logSql), ($logHash ?: bin2hex(openssl_random_pseudo_bytes(10))));
    }

    public function registrarAcaoLogado($acao, $descricao)
    {
        $modulo = defined('MODULO') ? MODULO : null;
        $usuario_cod = isset($_SESSION['usuario_cod']) ? $_SESSION['usuario_cod'] : null;

        $this->salvarlLog(['usuario_cod' => $usuario_cod,
            'modulo_cod' => $this->getDadosModulo($modulo)['modulo_cod'],
            'id' => null,
            'acao' => $acao,
            'log_descricao' => $descricao
            ], null, bin2hex(openssl_random_pseudo_bytes(10)));
    }

    public function registrarAcessoLogado()
    {
        $modulo = defined('MODULO') ? MODULO : null;
        $usuario_cod = isset($_SESSION['usuario_cod']) ? $_SESSION['usuario_cod'] : null;

        $this->salvarlLog(['usuario_cod' => $usuario_cod,
            'modulo_cod' => $this->getDadosModulo($modulo)['modulo_cod'],
            'id' => null,
            'acao' => 'Acessou'
            ], null, bin2hex(openssl_random_pseudo_bytes(10)));
    }

    /**
     * 
     * @param \Doctrine\DBAL\Query\QueryBuilder $sql
     * @param string $logHash
     */
    public function registraLog($sql, $logHash)
    {
        $sqlCompleta = $this->getSqlCompleta($sql);
        $actParams = $this->getActionParams();

        if (empty($actParams['id'])) {

            if ($sql->getType() === 1 or $sql->getType() === 2) {
                $actParams['id'] = $this->getIdRegistroSql($sql, $sqlCompleta);
            } elseif ($sql->getType() === 3) {
                $actParams['id'] = null;
            }
        }

        $this->salvarlLog($actParams, $sqlCompleta, $logHash);
    }

    private function getActionParams()
    {
        $modulox = defined('MODULO_SITE') ? MODULO_SITE : MODULO;

        $modulo = $this->getDadosModulo($modulox);
        $id = filter_input(INPUT_POST, 'cod');
        $tab = filter_input(INPUT_POST, 'n');
        $acao = filter_input(INPUT_GET, 'acao');

        if (isset($_SESSION['usuario_cod'])) {
            $usuario_cod = $_SESSION['usuario_cod'];
        } else {
            $usuario_cod = null;
        }

        return ['usuario_cod' => $usuario_cod,
            'modulo_cod' => $modulo['modulo_cod'],
            'id' => $id,
            'acao' => $acao,
            'tab' => $tab
        ];
    }

    /**
     * 
     * @param \Doctrine\DBAL\Query\QueryBuilder $sql
     * @param string $sqlCompleta
     * @return type
     */
    private function getIdRegistroSql($sql, $sqlCompleta)
    {      
        $parts = $this->getAtributosPrivados($sql->getQueryPart('where'));

        if (!isset($parts['parts'])) {
            return null;
        }

        $idRegistro = null;

        foreach ($parts['parts'] as $k => $v) {

            $param = preg_replace('/\s/', '', explode('=', $v)[0]);
            $matches = [];
            preg_match('/' . $param . '\s=\s[0-9]{1,}/', $sqlCompleta, $matches);

            if (!isset($matches[0])) {
                continue;
            }

            if (strpos($matches[0], '=') === false) {
                continue;
            }

            $valParam = (int) explode('=', $matches[0])[1];

            if ($valParam > 0) {
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

            $key = preg_replace(array('/' . addslashes('Doctrine\DBAL\Query\Expression\CompositeExpression') . '/', '/\W/'), '', $key);
            $attrs[$key] = $val;
        }

        return $attrs;
    }

    /**
     * 
     * @param \Doctrine\DBAL\Query\QueryBuilder $sql
     * @return type
     */
    private function getSqlCompleta($sql)
    {
        $params = $sql->getParameters();

        $paramTypes = array_map(function($param) {
            return (is_numeric($param) ? 1 : 2);
        }, $params);


        $sqlCompleta = $sql->getSQL();

        foreach ($paramTypes as $param => $type) {

            $replacement = ($type == 1 ? $params[$param] : "'" . $params[$param] . "'");
            $sqlCompleta = preg_replace(['/:' . $param . '/', '/\?/'], $replacement, $sqlCompleta, 1);
        }

        return $sqlCompleta;
    }

    protected function getDadosModulo($moduloNome)
    {
        $con = Conexao::conectar();

        if ($moduloNome) {
            $dados = $con->execLinha(parent::getDadosModuloSql($con, $moduloNome));

            return count($dados) ? $dados : ['modulo_cod' => null];
        }

        return ['modulo_cod' => null];
    }

    protected function salvarlLog($actParams, $sqlCompleta, $logHash)
    {
        parent::salvarLogSql($actParams, $sqlCompleta, $logHash)->execute();
    }

}
