<?php

namespace Pixel\Template\BarraSuperior\PesquisarOrganograma;

class PesquisarOrganogramaController extends \Zion\Core\Controller
{

    private $con;
    private $sql;
    private $class;
    private $form;

    public function __construct()
    {
        parent::__construct();

        $this->con = \Zion\Banco\Conexao::conectar();
        $this->sql = new \Pixel\Template\BarraSuperior\PesquisarOrganograma\PesquisarOrganogramaSql();
        $this->class = new \Pixel\Template\BarraSuperior\PesquisarOrganograma\PesquisarOrganogramaClass();
        $this->form = new \Pixel\Template\BarraSuperior\PesquisarOrganograma\PesquisarOrganogramaForm();
    }

    protected function iniciar()
    {

        $retorno = '';

        try {
            return $this->form->getForm();
        } catch (\Exception $ex) {

            $retorno = $ex;
        }
    }

    protected function setOrganogramaCod()
    {
        $cod = \filter_input(INPUT_GET, 'a');
        $_SESSION['organogramaCod'] = $cod;
        return $_SESSION['organogramaCod'];
    }

    protected function resetOrganogramaCod()
    {
        $getDadosUsuario = $this->class->getDadosUsuario();
        $organogramaCodUsuario = $getDadosUsuario['organogramacod'];

        $_SESSION['organogramaCod'] = $organogramaCodUsuario;
        return $_SESSION['organogramaCod'];
    }

}
