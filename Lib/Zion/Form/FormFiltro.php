<?/** * \Zion\Form\FormFiltro() *  * @author The Sappiens Team * @copyright Sappiens 2014 * @version 2014 * @access public */ namespace Zion\Form;class FormFiltro{    private $html;    /**     * FormFiltro::__construct()     *      * @return     */    public function __construct()    {        $this->html = new \Zion\Html\Html();    }    /**     * FormFiltro::montaFiltro()     *      * @param mixed $arrayCampos     * @return     */    public function montaFiltro($arrayCampos)    {        $html = '';        $hiddens = '';        $strFormI = $this->html->abreTagAberta('div', array('id' => 'sis_form_filtros')) .                $this->html->abreTagFechada('img', array('src', $_SESSION['UrlBase'] . 'figuras/sis_filtro_mostrar.gif', 'id' => 'imgSisFiltro', 'onClick' => 'sisShowFiltro()')) .                $this->html->fechaTag('div');        $strFormI .= $this->html->abreTagAberta('form', array('method' => 'get', 'name' => 'FormFiltro', 'id' => 'FormFiltro', 'onSubmit' => 'return false', 'style' => 'display:none')) .                $strFormF = $this->html->fechaTag('form');        if (is_array($arrayCampos)) {            $html = $this->html->abreTagAberta('table', array(''));            //Cria Linhas            foreach ($arrayCampos as $valor) {                if ($valor[0] == true) {                    if (!empty($valor[1])) {                        $linha[$valor[3]] .= $this->html->abreTagAberta('td', array('align' => 'right', 'nowrap' => 'nowrap')) . $valor[1] . $this->html->fechaTag('td');                    }                    $linha[$valor[3]] .= $this->html->abreTagAberta('td', array('nowrap' => 'nowrap')) . $valor[1] . $this->html->fechaTag('td');                } else {                    $escondido[] = $valor[2];                }            }            //Descarrega Campos Escondidos            if (is_array($escondido)) {                foreach ($escondido as $campos) {                    $hiddens .= $campos;                }            }            //Cria estrutura de Filtro            foreach ($linha as $conteudo) {                $html .= $this->html->abreTagAberta('tr', array(''));                $html .= $conteudo;                $html .= $hiddens;                $html .= $this->html->fechaTag('tr');            }            $html .= $this->html->fechaTag('table');            //Hidden de Intercepção a paginação            $html .= $this->html->abreTagFechada('input', array('name' => 'PaginaAtual', 'id' => 'SisPaginaAtual', 'type' => 'hidden', 'value' => ''));            $html .= $this->html->abreTagFechada('input', array('name' => 'QuemOrdena', 'id' => 'SisQuemOrdena', 'type' => 'hidden', 'value' => ''));            $html .= $this->html->abreTagFechada('input', array('name' => 'TipoOrdenacao', 'id' => 'SisTipoOrdenacao', 'type' => 'hidden', 'value' => ''));            return $strFormI . $html . $strFormF;        }        return $html;    }}