<?php

namespace Zion\Exportacao;

use Zion\Tratamento\Texto;
use Zion\Banco\Conexao;
use App\Sistema\Modulo\ModuloClass;
use Zion\Exception\ErrorException;
use Mpdf\Mpdf;

class PDF {

    private $con;

    public function __construct() {
        $this->con = Conexao::conectar();
    }

    public function impressaoGridPDF($dados, $cssFile, $cssPath, $controller, $logo, $orientacao = "P") {

        $texto = Texto::instancia();

        $nomeModulo = (new ModuloClass())->getDadosModulo(MODULO)['modulo_nome_menu'];

        $tituloRelatorio = (filter_input(INPUT_GET, 'sisUFC') ? $this->getUsuarioFiltroNome(filter_input(INPUT_GET, 'sisUFC')) : "Relatório do módulo " . $nomeModulo);
        $nomeArquivo = preg_replace('/[^A-z]/', '-', strtolower($texto->removerAcentos($tituloRelatorio))) . '_' . date('d-m-Y_H-i-s') . '.pdf';

        try {

            if (count($dados) < 1) {
                throw new Exception('Nenhum dado a ser exibido!');
            }

            $pdfPath = SIS_DIR_BASE . 'Storage/PDF/';

            $stylesheet = $this->loadCss($cssFile, $cssPath);

            $dadosHtml = json_decode($dados['retorno'], true);

            $html = $controller->layout()->render('impressao_grid_pdf.html.twig', [
                'grid' => ['retorno' => $dadosHtml],
                'logo' => $logo,
                'modulo' => $nomeModulo,
                'titulo' => $tituloRelatorio,
                'dataRelatorio' => date("d/m/Y \à\s H:i:s")
            ]);

            $mpdf = new Mpdf(['tempDir' => $pdfPath, 'format' => 'A4-' . $orientacao]);

            $mpdf->WriteHTML($stylesheet, 1);
            $mpdf->WriteHTML($html);

            $mpdf->Output($pdfPath . uniqid() . '_relatorio_' . strtolower(MODULO) . '_' . date('d-m-Y') . '.pdf', 'F');
            $mpdf->Output($nomeArquivo, 'D');
        } catch (\Exception $e) {
            throw new ErrorException($e->getMessage());
        }
    }

    public function getUsuarioFiltroNome($sisUFC) {
        if (!empty($sisUFC)) {
            $qb = $this->con->qb();

            $qb->select('*')
                    ->from('_usuario_filtro')
                    ->where($qb->expr()->eq('usuarioFiltroCod', ':usuarioFiltroCod'))
                    ->setParameter('usuarioFiltroCod', $sisUFC, \PDO::PARAM_INT)
                    ->setMaxResults(1);

            $linha = $this->con->execLinha($qb);

            if (isset($linha['usuariofiltronomerelatorio'])) {
                return $linha['usuariofiltronomerelatorio'];
            } else {
                return false;
            }
        }
    }

    private function loadCss($cssFile, $cssPath = false) {
        if ($cssPath === false) {
            $cssPath = SIS_URL_DEFAULT_BASE . 'Tema/Vendor/Pixel/1.3.0/stylesheets/';
        }

        $files = preg_replace('/\\n/', '', file_get_contents($cssPath . $cssFile));

        $css = NULL;
        $matches = [];

        if (preg_match_all('/[\@import\surl(\']{13}/', $files, $matches, \PREG_OFFSET_CAPTURE)) {

            foreach ($matches[0] as $val) {


                $start = $val[1] + (strlen($val[0]));
                $end = [];

                preg_match('/[\')]{2}/', $files, $end, PREG_OFFSET_CAPTURE, $start);

                if (isset($end[0][1]) === false) {
                    continue;
                }

                $length = ($end[0][1] - $start);

                $file = substr($files, ($start), ($length));

                if (!preg_match('/[http\:\/\/]{7}|[https\:\/\/]{8}/', $file)) {
                    $urlFile = $cssPath . $file;
                } else {
                    $urlFile = $file;
                }

                $css .= preg_replace('/\\n/', '', \file_get_contents($urlFile));
            }
        }

        return ($files . $css);
    }

}
