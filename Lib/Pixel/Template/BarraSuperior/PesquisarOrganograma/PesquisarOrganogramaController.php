<?php

namespace Pixel\Template\BarraSuperior\PesquisarOrganograma;

class PesquisarOrganogramaController extends \Zion\Core\Controller
{

    private $pesquisarOrganogramaClass;
    private $pesquisarOrganogramaForm;

    public function __construct()
    {
        $this->pesquisarOrganogramaClass = new \Pixel\Template\BarraSuperior\PesquisarOrganograma\PesquisarOrganogramaClass();
        $this->pesquisarOrganogramaForm = new \Pixel\Template\BarraSuperior\PesquisarOrganograma\PesquisarOrganogramaForm();
    }  

    protected function iniciar()
    {

        $retorno = '';

        try {

            //$template = new \Pixel\Template\Template();
            return $this->pesquisarOrganogramaForm->getForm();

        } catch (\Exception $ex) {
            
            $retorno = $ex;
        }

        //$template->setConteudoMain($retorno);
        //return $template->getTemplate();
        
    }

    protected function setOrganogramaCod()
    {
        //new \Zion\Acesso\Acesso('filtrar');

        $cod = \filter_input(INPUT_GET, 'a');
        $_SESSION['organogramaCod'] = $cod;
        return $_SESSION['organogramaCod'];       
    }    

    protected function resetOrganogramaCod()
    {
        //new \Zion\Acesso\Acesso('filtrar');

        $con = \Zion\Banco\Conexao::conectar();
        $sql = new \Sappiens\Dashboard\DashboardSql();
        $getDadosUsuario = $con->execLinhaArray($sql->getDadosUsuario($_SESSION['usuarioCod']));          
        $organogramaCodUsuario = $getDadosUsuario['organogramaCod'];        

        $_SESSION['organogramaCod'] = $organogramaCodUsuario;
        return $_SESSION['organogramaCod'];       
    } 

}
