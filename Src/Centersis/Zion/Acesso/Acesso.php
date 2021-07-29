<?phpnamespace Centersis\Zion\Acesso;use Centersis\Zion\Exception\AcessoException;class Acesso extends AcessoVO{    private $acessoSql;    private $con;   /**    * Ao iniciar a intancia com o parametro de ação, será verificado    * automaticamente se existe permissão para a ação.    * Este método tambem seta o nome do módulo que previamente deveria existir    * como constante e tambem o código do usuário ativo que previamente    * deveria existir na sessão ativa.    * @param string $acaoModuloIdPermissao    * @throws Zion\Exception\AcessoException    */    public function __construct($acaoModuloIdPermissao = '')    {        $this->con = \Zion\Banco\Conexao::conectar();        $this->acessoSql = new AcessoSql();        if(defined('MODULO')) {                    parent::setModuloNome(constant('MODULO'));                    }                if(isset($_SESSION['usuario_cod'])) {                    parent::setUsuarioCod($_SESSION['usuario_cod']);                    }                if(!empty($acaoModuloIdPermissao)){            if(!$this->permissaoAcao($acaoModuloIdPermissao)){                throw new AcessoException('Sem permissão');            }        }    }    /**     * Ao informar a acão, o metodo verifica se o usuário tem acesso a ela (ação),     * este método captura automaticamente o usuario_cod da sessão e nome do      * módulo que deve ser definido como variavel constante     * @param string $acaoModuloIdPermissao     * @return bool     */    public function permissaoAcao($acaoModuloIdPermissao)    {        return (bool) $this->con->execNLinhas($this->acessoSql->permissaoAcao(parent::getModuloNome(), $acaoModuloIdPermissao, parent::getusuarioCod()));    }    /**     * Retorna os dados de um módulo, o módulo em questão estaria definido previamente     * como uma constante     * @return array     */    public function dadosModulo()    {        return $this->con->execLinha($this->acessoSql->dadosModulo(parent::getModuloNome()));    }    /**     * Retorna os dados de uma ação de um módulo,     * este método captura automaticamente o usuario_cod da sessão e nome do      * módulo que deve ser definido como variavel constante     * @param string $acaoModuloIdPermissao     * @return array     */    public function dadosAcaoModulo($acaoModuloIdPermissao)    {        return $this->con->execLinha($this->acessoSql->dadosAcaoModulo(parent::getModuloNome(), $acaoModuloIdPermissao));    }    /**     * Retorna as opções de módulo que o usuário tem direito     * @return array     */    public function permissoesModulo($posicao = null, $indice = 'acao_modulo_id_permissao')    {        return $this->con->paraArray($this->acessoSql->permissoesModulo(parent::getModuloNome(), parent::getusuarioCod()),$posicao, $indice);    }        /**     * Retorna as ações de módulo invisíveis que o usuário tem direito     * @return array     */    public function acoesModulo($condicao = '')    {        return $this->con->paraArray($this->acessoSql->acoesModulo(parent::getModuloNome(), parent::getusuarioCod(), $condicao));    }            /**     * Retorna os dados de uma ação de um módulo atraves de seu Cod,     * @param string $acaoModuloCod     * @return array     */    public function dadosAcaoModuloCod($acaoModuloCod)    {        return $this->con->execLinha($this->acessoSql->dadosAcaoModuloCod(parent::getModuloNome(), $acaoModuloCod));    }    }