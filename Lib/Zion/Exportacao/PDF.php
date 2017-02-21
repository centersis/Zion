<?php

namespace Zion\Exportacao;

class PDF 
{
    
    private $con;
    
    public function __construct() 
    {
        $this->con = \Zion\Banco\Conexao::conectar();
    }

    public function impressaoGridPDF($dados, $cssFile, $cssPath, $controller, $logo, $orientacao = "P")
    {
        $texto = \Zion\Tratamento\Texto::instancia();
        
        $nomeModulo = (new \Base\Sistema\Modulo\ModuloClass())->getDadosModulo(MODULO)['modulonomemenu'];
        
        $tituloRelatorio    = (\filter_input(\INPUT_GET, 'sisUFC') ? $this->getUsuarioFiltroNome(\filter_input(\INPUT_GET, 'sisUFC')) : "Relatório do módulo ". $nomeModulo);
        $nomeArquivo        = \preg_replace('/[^A-z]/', '-', \strtolower($texto->removerAcentos($tituloRelatorio))) . '_'. \date('d-m-Y_H-i-s') .'.pdf';

        try {

            if(\count($dados) < 1){
                throw new \Exception('Nenhum dado a ser exibido!');
            }
            
            $pdfPath = \SIS_DIR_BASE .'Storage/PDF/';

            include_once(SIS_FM_BASE . 'Lib/mPDF/mpdf.php');
            
            $stylesheet = $this->loadCss($cssFile, $cssPath);
            
            $dadosHtml = \json_decode($dados['retorno'], true);

            $html = $controller->layout()->render('impressao_grid_pdf.html.twig', [
                                                    'grid'              => ['retorno' => $dadosHtml],
                                                    'logo'              => $logo,
                                                    'modulo'            => $nomeModulo,
                                                    'titulo'            => $tituloRelatorio,
                                                    'dataRelatorio'     => date("d/m/Y \à\s H:i:s")
                                                   ]);

            $mpdf = new \mPDF('c', 'A4-'. \strtoupper($orientacao));

            $mpdf->CurOrientation = "L";

            $mpdf->allow_charset_conversion = true;
            $mpdf->charset_in    = 'UTF-8';
            
            if(\strlen($dadosHtml['legenda']) > 5){
                $mpdf->SetHTMLFooter($dadosHtml['legenda']);
            } else {
                $mpdf->SetFooter('{PAGENO}/{nbpg}');
            }
            
            $mpdf->WriteHTML($stylesheet, 1);
            $mpdf->WriteHTML($html);

            $mpdf->Output($pdfPath . uniqid() .'_relatorio_'. \strtolower(MODULO) .'_'. date('d-m-Y') .'.pdf', 'F');
            $mpdf->Output($nomeArquivo, 'D');

         } catch(\Exception $e) {
             throw new \Zion\Exportacao\Exception($e->getMessage());
         }
    }
    
    public function getUsuarioFiltroNome($sisUFC)
    {
        if(!empty($sisUFC)){
            $qb = $this->con->qb();
            
            $qb->select('*')
               ->from('_usuario_filtro')
               ->where($qb->expr()->eq('usuarioFiltroCod', ':usuarioFiltroCod'))
               ->setParameter('usuarioFiltroCod', $sisUFC, \PDO::PARAM_INT)
               ->setMaxResults(1);
            
            $linha = $this->con->execLinha($qb);
            
            if(isset($linha['usuariofiltronomerelatorio'])){
                return $linha['usuariofiltronomerelatorio'];
            } else {
                return false;
            }
        }
    }
    
    private function loadCss($cssFile, $cssPath = false) 
    {
        if($cssPath === false){
            $cssPath = \SIS_URL_DEFAULT_BASE . 'Tema/Vendor/Pixel/1.3.0/stylesheets/';
        }
        
        $files = \preg_replace('/\\n/', '', \file_get_contents($cssPath . $cssFile));
        
        $css = NULL;
        $matches = [];
        
        if(\preg_match_all('/[\@import\surl(\']{13}/', $files, $matches, \PREG_OFFSET_CAPTURE)){

            foreach ($matches[0] as $val) {


                $start = $val[1] + (\strlen($val[0]));
                $end = [];

                \preg_match('/[\')]{2}/', $files, $end, PREG_OFFSET_CAPTURE, $start);

                if (isset($end[0][1]) === false) {
                    continue;
                }
                
                $length = ($end[0][1] - $start);
                
                $file = \substr($files, ($start), ($length));

                if (!preg_match('/[http\:\/\/]{7}|[https\:\/\/]{8}/', $file)) {
                    $urlFile = $cssPath . $file;
                } else {
                    $urlFile = $file;
                }

                $css .= \preg_replace('/\\n/', '', \file_get_contents($urlFile));
            }
        }

        return ($files . $css);
    }

    public function imprimeRelatorioPDF($html, $cssFile = NULL, $cssPath = NULL, $tituloRelatorio = NULL, $legenda = false, $orientacao = "P", $output = "D")
    {
        $texto = \Zion\Tratamento\Texto::instancia();

        $nomeArquivo    = \preg_replace('/[^A-z|^0-9]/', '-', \strtolower($texto->removerAcentos($tituloRelatorio))) . '_'. \date('d-m-Y_H-i-s') .'.pdf';
        
        try {

            if(\count($html) < 1){
                throw new \Exception('Nenhum dado a ser exibido!');
            }
            
            $pdfPath = \SIS_DIR_BASE .'Storage/PDF/';
            
            if(!\is_readable($pdfPath)){
                \mkdir($pdfPath, 0777);
            }

            include_once(SIS_FM_BASE . 'Lib/mPDF/mpdf.php');
            
            if($cssPath !== NULL and $cssFile !== NULL){
                $stylesheet = $this->loadCss($cssFile, $cssPath);
            }

            $mpdf = new \mPDF('c', 'A4-'. \strtoupper($orientacao));

            $mpdf->allow_charset_conversion = true;
            $mpdf->charset_in    = 'UTF-8';
            
            if(\strlen($legenda) > 5){
                $mpdf->SetHTMLFooter($legenda);
            } else {
                $mpdf->SetFooter('{PAGENO}/{nbpg}');
            }
            
            $mpdf->WriteHTML($stylesheet, 1);
            $mpdf->WriteHTML($html);
            $mpdf->Output($nomeArquivo, $output);
            
            return false;

         } catch(\Exception $e) {
            return $e->getMessage();
         }
    }
    
    public function salvaArquivoPDF($html, $cssFile = NULL, $cssPath = NULL, $filePathName = NULL, $legenda = false, $orientacao = "P")
    {        
        try {

            if(\count($html) < 1){
                throw new \Exception('Nenhum dado a ser exibido!');
            }

            include_once(\SIS_FM_BASE . 'Lib/mPDF/mpdf.php');
            
            if($cssPath !== NULL and $cssFile !== NULL){
                $stylesheet = $this->loadCss($cssFile, $cssPath);
            }

            $mpdf = new \mPDF('c', 'A4-'. \strtoupper($orientacao));

            $mpdf->allow_charset_conversion = true;
            $mpdf->charset_in    = 'UTF-8';

            if($legenda !== false){
                $mpdf->SetHTMLFooter($legenda);
            } else {
                $mpdf->SetFooter('{PAGENO}/{nbpg}');
            }

            $mpdf->WriteHTML($stylesheet, 1);
            $mpdf->WriteHTML($html);
            $mpdf->Output($filePathName, 'F');

            return false;

         } catch(\Exception $e) {
            return $e->getMessage();
         }
    }
}
