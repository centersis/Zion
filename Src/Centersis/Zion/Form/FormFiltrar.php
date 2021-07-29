<?phpnamespace Centersis\Zion\Form;class FormFiltrar{    private $fPHP;    private $objForm;    private $situacao; //Situação do Filtro;    private $operadores = array();    public function __construct($obj = null)    {        $this->fPHP = new FuncoesPHP();        $this->setSituacao(false);        if (\is_object($obj)) {            $this->objForm = $obj;        }        $this->operadores = array(            "=" => "=",            ">" => ">",            "<" => "<",            ">=" => ">=",            "<=" => "<=",            "<>" => "<>",            "*A" => "*A",            "A*" => "A*",            "*" => "*",            "E" => "E",            "OU" => "OU");    }    public function getStringSql($nomeCampo, $campoBanco, $tipoFiltro = null)    {        $sql = "";        //Recupera Valores do Formulário        $operador = \html_entity_decode($_GET["hidden_sis_filtro_" . $nomeCampo]);        $tipoCampo = $_GET["hiddent_sis_filtro_" . $nomeCampo];        //Intercepta valor        $valor = $this->objForm->getCampoFiltro($nomeCampo, $tipoFiltro);        //Valida Informações        if ($operador == "" or $tipoCampo == "") {            if ($valor <> "") {                $this->setSituacao(true);                return " AND " . $campoBanco . " = '" . $valor . "' ";            }            return $sql;        }        //Retorna Sql	        if ("$valor" <> "" or ( $operador == "E" or $operador == "OU")) {            if (in_array($operador, $this->operadores)) {                switch ($operador) {                    case "=": case ">": case "<": case ">=": case "<=": case "<>":                        if ($tipoCampo <> "Float" and $tipoCampo <> "Inteiro") {                            $valor = "'$valor'";                        }                        $sql = " AND $campoBanco $operador $valor ";                        $this->setSituacao(true);                        break;                    case "*A":                        $sql = " AND $campoBanco LIKE '%$valor' ";                        $this->setSituacao(true);                        break;                    case "A*":                        $sql = " AND $campoBanco LIKE '$valor%' ";                        $this->setSituacao(true);                        break;                    case "*":                        $sql = " AND $campoBanco LIKE '%$valor%' ";                        $this->setSituacao(true);                        break;                    case "E":                        $sql = $this->eOrSql($campoBanco, $nomeCampo, $tipoFiltro, "E");                        break;                    case "OU":                        $sql = $this->eOrSql($campoBanco, $nomeCampo, $tipoFiltro, "OR");                        break;                    default:                        $sql = '';                        break;                }            }        }        return $sql;    }    private function eOrSql($campoBanco, $nomeCampo, $tipoFiltro, $clausula)    {        $operadorB = "";        $valorB = "";        $multiplo = is_array($_GET[$nomeCampo . "A"]);        $operadorA = html_entity_decode($_GET["hidden_sis_filtro_" . $nomeCampo . "A"]);        $valorA = trim($_GET[$nomeCampo . "A"]);        if (!$multiplo) {            $operadorB = html_entity_decode($_GET["hidden_sis_filtro_" . $nomeCampo . "B"]);            $valorB = trim($_GET[$nomeCampo . "B"]);        }        //Recupera Tipo de Campo        $tipoCampo = $_GET["hiddent_sis_filtro_" . $nomeCampo];        //Validação de Operadores        if (($operadorA == "" and $operadorB == "") or $tipoCampo == "") {            return "";        }        //Validação de Valores        if ($valorA == "" and $valorB == "") {            return "";        }        //Seta e Recupera Valores        if ($valorA <> "") {            //Valida Opreador A            if (!in_array($operadorA, $this->operadores)) {                return "";            }            //Seta Valores            if (!$multiplo) {                $this->objForm->setCampoRetorna($nomeCampo . "A", $valorA);                $valorA = $this->objForm->getCampoFiltro($nomeCampo . "A", $tipoFiltro);            }        }        if ($valorB <> "") {            //Valida Opreador B            if (!in_array($operadorB, $this->operadores)) {                return "";            }            //Seta Valores            $this->objForm->setCampoRetorna($nomeCampo . "B", $valorB);            $valorB = $this->objForm->getCampoFiltro($nomeCampo . "B", $tipoFiltro);        }        if ($tipoCampo <> "Float" and $tipoCampo <> "Inteiro" and ! $multiplo) {            if ($valorA <> "") {                $valorA = "'$valorA'";            }            if ($valorB <> "") {                $valorB = "'$valorB'";            }        }        //Se valor a vazio e b não inverte        if ($valorB <> "" and $valorA == "") {            $valorA = $valorB;            $operadorA = $operadorB;            $valorB = "";            $operadorB = "";        }        //Se os dois operadores são iguais mude para ow a não ser que seja <> ow =        if ($valorB <> "")            if (($operadorA == $operadorB and $operadorA <> "<>") or $operadorB == "=") {                $clausula = "OR";            }        if ($multiplo) {            if ($_GET[$nomeCampo . "A"][0] == "" and count($_GET[$nomeCampo . "A"]) <= 1) {                return '';            }            $sql = " AND $campoBanco IN ( ";            foreach ($_GET[$nomeCampo . "A"] as $valorSelecionado) {                if ($valorSelecionado <> "") {                    $sql .= " '" . $this->fPHP->converteHTML($valorSelecionado) . "' ,";                }            }            $sql = \substr($sql, 0, -1);            $sql .= " ) ";        } else {            if ($clausula == "E") {                if ($valorB <> "") {                    $sql = " AND $campoBanco $operadorA $valorA AND $campoBanco $operadorB $valorB ";                } else {                    $sql = " AND $campoBanco $operadorA $valorA ";                }                $this->setSituacao(true);            } elseif ($clausula == "OR") {                if ($valorB <> "") {                    $sql = " AND (($campoBanco $operadorA $valorA) OR ($campoBanco $operadorB $valorB)) ";                } else {                    $sql = " AND $campoBanco $operadorA $valorA ";                }                $this->setSituacao(true);            }        }        return $sql;    }    function getHiddenParametros($arrayParametros)    {        $retorno = array();        if (is_array($arrayParametros) and ! empty($arrayParametros)) {            foreach ($arrayParametros as $campo) {                //Intecepta E ou OU                $campoEOU = $_GET['hidden_sis_filtro_' . $campo];                if ($campoEOU == "E" or $campoEOU == "OU") {                    $valorA = $_GET[$campo . "A"];                    $valorB = $_GET[$campo . "B"];                    $opcaoA = $_GET['hidden_sis_filtro_' . $campo . "A"];                    $opcaoB = $_GET['hidden_sis_filtro_' . $campo . "B"];                    $tipo = $_GET['hiddent_sis_filtro_' . $campo];                    if ($valorA <> "") {                        if ($opcaoA <> "" and $tipo <> "") {                            $retorno[] = $campo . "A";                            $retorno[] = 'hidden_sis_filtro_' . $campo . "A";                        }                    }                    if ($valorB <> "") {                        if ($opcaoB <> "" and $tipo <> "") {                            $retorno[] = $campo . "B";                            $retorno[] = 'hidden_sis_filtro_' . $campo . "B";                        }                    }                    //Tipo E e Ou                    if ($valorA <> "" or $valorB <> "") {                        $retorno[] = 'hidden_sis_filtro_' . $campo;                        $retorno[] = 'hiddent_sis_filtro_' . $campo;                    }                } else {                    $valor = $_GET[$campo];                    $opcao = $_GET['hidden_sis_filtro_' . $campo];                    $tipo = $_GET['hiddent_sis_filtro_' . $campo];                    if ($valor <> "") {                        if ($opcao <> "" and $tipo <> "") {                            $retorno[] = 'hidden_sis_filtro_' . $campo;                            $retorno[] = 'hiddent_sis_filtro_' . $campo;                        }                    }                }            }            return $retorno;        } else {            return $retorno;        }    }    //Metodo para interceptar registros selecionados da grid    public function printSql($cheveCompara, $array)    {        $sql = ' AND ' . $cheveCompara . ' IN (';        if (\is_array($array) and \count($array) > 0) {            $sql .= \implode(',', $array);            $this->setSituacao(true);        } else {            return "";        }        return $sql . ') ';    }    public function setSituacao($valor)    {        $this->situacao = $valor === true ? true : false;    }    public function getSituacao()    {        return $this->situacao;    }}