<?php

namespace Centersis\Zion\Boleto;

use Centersis\Zion\Tratamento\Tratamento;
use Centersis\Zion\Boleto\BoletoPHP\BB18Class;
use Centersis\Zion\Boleto\BoletoPHP\BancoobClass;
use Centersis\Zion\Boleto\BoletoPHP\SicrediClass;
use Centersis\Zion\Boleto\BoletoPHP\BanrisulClass;
use Centersis\Zion\Boleto\BoletoPHP\CecredClass;
use Centersis\Zion\Boleto\BoletoPHP\SafraClass;
use Centersis\Zion\Boleto\BoletoPHP\CaixaClass;
use Centersis\Zion\Boleto\BoletoPHP\SantanderClass;
use Centersis\Zion\Boleto\BoletoPHP\BradescoClass;
use Centersis\Zion\Boleto\BoletoPHP\ItauClass;
use Centersis\Zion\Boleto\BoletoPHP\UnicredClass;
use App\Financeiro\Movimentacao\Sistema\ContaClassFactory;
use App\Financeiro\Movimentacao\Sistema\MovimentacaoClassFactory;
use App\Financeiro\Movimentacao\Sistema\ParcelaClassFactory;
use App\Financeiro\Parametrizacao\Sistema\ParametrizacaoClassFactory;
use Centersis\Zion\Exception\ErrorException;

abstract class BoletoClass implements IBoletoClass
{

    protected $movimentacaoClass;
    protected $parametrizacaoClass;
    protected $parcelaClass;
    protected $organogramaCod;
    /**
     * @var \Zion\Banco\Conexao 
     */
    protected $con;

    public function __construct($organogramaCod, $con)
    {
        $this->organogramaCod = $organogramaCod;
        $this->con = $con;

        $this->movimentacaoClass = new (MovimentacaoClassFactory())->instancia($this->organogramaCod);
        $this->parametrizacaoClass = (new ParametrizacaoClassFactory)->instancia($this->organogramaCod);
        $this->parcelaClass = new (ParcelaClassFactory())->instancia($this->organogramaCod);
    }

    protected function getDadosBoleto($dadosParcela, $parametrizacao)
    {
        switch ($parametrizacao['fnc_convenio_banco']) {
            case '001': return (new BB18Class())->getDadosBoleto($dadosParcela, $parametrizacao);
            case '033': return (new SantanderClass())->getDadosBoleto($dadosParcela, $parametrizacao);
            case '041': return (new BanrisulClass())->getDadosBoleto($dadosParcela, $parametrizacao);
            case '085': return (new CecredClass())->getDadosBoleto($dadosParcela, $parametrizacao);
            case '104': return (new CaixaClass())->getDadosBoleto($dadosParcela, $parametrizacao);
            case '136': return (new UnicredClass())->getDadosBoleto($dadosParcela, $parametrizacao);
            case '237': return (new BradescoClass())->getDadosBoleto($dadosParcela, $parametrizacao);
            case '341': return (new ItauClass())->getDadosBoleto($dadosParcela, $parametrizacao);
            case '422': return (new SafraClass())->getDadosBoleto($dadosParcela, $parametrizacao);
            case '748': return (new SicrediClass())->getDadosBoleto($dadosParcela, $parametrizacao);
            case '756': return (new BancoobClass())->getDadosBoleto($dadosParcela, $parametrizacao);
            default : throw new ErrorException('Não existe suporte para o banco informado!');
        }
    }

    protected function montaDestinoContabilHtml($fncParcelaCod, $dadosMovimentacao, $dadosParcela)
    {
        $tratar = Tratamento::instancia();

        $conta = (new ContaClassFactory())->instancia($this->organogramaCod);

        $destinos = $conta->destinoContabil($fncParcelaCod, 'Manual');
        $html = [];

        $html[] = '<div style="font-size:14px; padding-top:2px; padding-bottom:2px;"><strong>' . $dadosMovimentacao['fnc_lancamento_nome'] . '</strong></div>';

        $totalDespesas = 0;

        if ($dadosParcela['fnc_parcela_valor'] > '0.01') {
            foreach ($destinos as $dados) {

                $totalDespesas += $dados['fnc_conta_valor'];

                $html[] = '<div style="font-size:12px; padding-top:2px; padding-bottom:2px;">' .
                    $tratar->numero()->floatCliente($dados['fnc_conta_valor']) . ' <i class="fa fa-long-arrow-right"></i> ' .
                    $dados['fnc_conta_contabil_nome'] . '</div>';
            }
        }

        if ($dadosParcela['fnc_parcela_pago'] === 'S') {

            if ($dadosParcela['fnc_parcela_valor'] > $dadosParcela['fnc_parcela_valor_pago']) {

                $valorDesconto = $dadosParcela['fnc_parcela_valor'] - $dadosParcela['fnc_parcela_valor_pago'];

                $html[] = '<div style="font-size:12px; padding-top:2px; padding-bottom:2px;">' .
                    $tratar->numero()->floatCliente($valorDesconto) . ' <i class="fa fa-long-arrow-right"></i> Desconto</div>';
            } else if ($dadosParcela['fnc_parcela_valor'] < $dadosParcela['fnc_parcela_valor_pago']) {
                
            }
        }

        if ($dadosParcela['fnc_parcela_juros'] > 0) {

            $totalDespesas += $dadosParcela['fnc_parcela_juros'];

            $html[] = '<div style="font-size:12px; padding-top:2px; padding-bottom:2px;">' .
                $tratar->numero()->floatCliente($dadosParcela['fnc_parcela_juros']) . ' <i class="fa fa-long-arrow-right"></i> Acréscimos </div>';
        }

        if ($dadosParcela['fnc_parcela_desconto'] > 0) {

            $totalDespesas -= $dadosParcela['fnc_parcela_desconto'];

            $html[] = '<div style="font-size:12px; padding-top:2px; padding-bottom:2px;">' .
                $tratar->numero()->floatCliente($dadosParcela['fnc_parcela_desconto']) . ' <i class="fa fa-long-arrow-right"></i> Descontos </div>';
        }

        if ($dadosParcela['fnc_parcela_valor'] > '0.01') {
            $html[] = '<div style="font-size:12px; padding-top:2px; padding-bottom:2px;"><strong>
                Total das despesas: ' . $tratar->numero()->moedaCliente($totalDespesas) . '</strong></div>';
        }

        if ($dadosMovimentacao['fnc_lancamento_observacao']) {
            $html[] = '<div style="font-size:12px; padding-top:2px; padding-bottom:2px;">' . $dadosMovimentacao['fnc_lancamento_observacao'] . '</div>';
        }

        return implode('', $html);
    }

}
