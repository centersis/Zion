<?php

namespace Zion\Arquivo;

use Zion\Exception\ErrorException;
use Zion\Exception\ValidationException;

class ManipulaImagem extends ManipulaArquivo
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Verifica se as funções nescessárias para manipulação básica de imagens 
     * estão disponíveis no servidor
     */
    public function vericaFuncoesDeImagem()
    {
        $tI = "Função de manipulação "; //Texto Inicial
        $tF = " não existe, entre em contato com o administrador do sistema."; //Texto Final

        if (!\function_exists('getimagesize')) {
            throw new ErrorException($tI . "getimagesize" . $tF);
        }

        if (!\function_exists('imagecreatefromjpeg')) {
            throw new ErrorException($tI . "imagecreatefromjpeg" . $tF);
        }

        if (!\function_exists('imagecreatetruecolor')) {
            throw new ErrorException($tI . "imagecreatetruecolor" . $tF);
        }

        if (!\function_exists('imagecolorallocate')) {
            throw new ErrorException($tI . "imagecolorallocate" . $tF);
        }

        if (!\function_exists('imagecopyresampled')) {
            throw new ErrorException($tI . "imagecopyresampled" . $tF);
        }

        if (!\function_exists('imagesy')) {
            throw new ErrorException($tI . "imagesy" . $tF);
        }

        if (!\function_exists('imagesx')) {
            throw new ErrorException($tI . "imagesx" . $tF);
        }

        if (!\function_exists('imagejpeg')) {
            throw new ErrorException($tI . "imagejpeg" . $tF);
        }
    }

    /**
     * Retorna um array com a altura e a largura da imagem
     * ['L' => 00, 'A' => 00]
     * 
     * Esta função verifica se o arquivo existe, se não existir retorna como
     * resultado um array de de proporções Zero para Altura e Largura
     * @param link $imagem
     * @return array
     */
    public function dimensaoImagem($imagem)
    {
        if ($this->arquivoExiste($imagem)) {
            $imgSize = \getimagesize($imagem);
            return ["L" => $imgSize[0], "A" => $imgSize[1]];
        }

        return ["L" => 0, "A" => 0];
    }

    /**
     * Retorna um array com altura e largura proporcionais da imagem
     * @param int $tamanho
     * @param string $por A -> Altura ou L -> Largura
     * @param array $originais Altura e Largura Originais
     * @return array     
     */
    public function proporcoesImagem($tamanho, $por, $originais, $arredondarCasas = 0)
    {
        if ($originais['A'] == 0 or $originais['L'] == 0) {
            throw new ValidationException("Proporções do arquivo invalidas, certifique-se que o arquivo é mesmo uma imagem!");
        }

        switch ($por) {
            case "A":
                $calcula = ($tamanho * 100) / $originais['A'];
                $largura = \round(($originais['L'] * $calcula) / 100, $arredondarCasas);

                return ["L" => $largura, "A" => $tamanho];

            case "L":
                $calcula = ($tamanho * 100) / $originais['L'];
                $altura = \round(($originais['A'] * $calcula) / 100, $arredondarCasas);
                return ["L" => $tamanho, "A" => $altura];
        }
    }

    /**
     * Armazena e redimenciona uma imagem no formato JPG | GIF | PNG
     * @param string $nomeImagem
     * @param link $origem - Caminho fisico do imagem
     * @param link $destino - Caminho fisico onde será gravada a imagem
     * @param int $altura
     * @param int $largura
     */
    public function uploadImagem($nomeImagem, $origem, $destino, $altura = 0, $largura = 0, $qualidade = 100)
    {
        $postMax = \ini_get("post_max_size");
        $upMax = \ini_get("upload_max_filesize");

        //Menor Tamanho de Configuração
        $tMax = $postMax > $upMax ? $upMax : $postMax;

        //Verifica a integridade do arquivo
        if (!$this->arquivoExiste($origem)) {
            throw new ValidationException("O Arquivo não foi carregado, certifique-se que o tamanho do arquivo não tenha ultrapassado " . $tMax . " pois, este tamanho é o maximo permitido pelo seu servidor.");
        }

        //Verifica se a pasta permite gravação
        if (!$this->permiteEscrita(\dirname($destino))) {
            throw new ErrorException("A pasta onde você esta tentando gravar o arquivo não tem permissão de escrita, contate o administrador do sistema.$destino");
        }

        //Verifica se o arquivo ja existe
        if ($this->arquivoExiste($destino)) {
            //Se sim verifica se tem permissão para substitui-lo
            if (!$this->permiteEscrita($destino)) {
                throw new ErrorException("Este arquivo já existe e você não tem permissão para substituí-lo.");
            }
        }

        if ($qualidade > 100 or $qualidade < 0) {
            throw new ErrorException("Qualidade deve ser um número inteiro entre 1 e 100");
        }

        //Verifica a existencia das funãoes
        $this->vericaFuncoesDeImagem();

        //Recupera a Extensao do Arquivo
        $extensao = \strtolower($this->extenssaoArquivo($nomeImagem));

        //Cria um vetor com as proporãães da imagem
        $tArquivo = $this->dimensaoImagem($origem);

        //Calcula as proporções
        if (empty($altura) and empty($largura)) {

            $proporcao = ["L" => $tArquivo['L'], "A" => $tArquivo['A']];
        } elseif (!empty($altura) and!empty($largura)) {

            $proporcao = ["L" => $largura, "A" => $altura];
        } elseif (!empty($altura)) {

            $proporcao = $this->proporcoesImagem($altura, "A", $tArquivo);
        } else {

            $proporcao = $this->proporcoesImagem($largura, "L", $tArquivo);
        }

        //Executando a criaãão da nova imagem
        if ($extensao == 'jpg' or $extensao == 'jpeg') {
            $origem = @\imagecreatefromjpeg($origem);
        } elseif ($extensao == 'gif') {
            $origem = @\imagecreatefromgif($origem);
        } elseif ($extensao == 'png') {

            $origem = imagecreatefrompng($origem);

            imagesavealpha($origem, true);
            $img = imagecreatetruecolor($proporcao['L'], $proporcao['A']);

            $background = imagecolorallocatealpha($img, 255, 255, 255, 127);
            imagecolortransparent($img, $background);
            imagealphablending($img, false);
            imagesavealpha($img, true);
        } else {
            throw new ValidationException("Extensao inválida!");
        }

        if (!$origem) {
            throw new ErrorException("Não foi possivel gerar o arquivo!");
        }

        if ($extensao != 'png') {
            $img = \imagecreatetruecolor($proporcao['L'], $proporcao['A']);
        }

        if (\imagecopyresampled($img, $origem, 0, 0, 0, 0, $proporcao['L'], $proporcao['A'], \imagesx($origem), \imagesy($origem))) {
            if ($extensao == 'jpg' or $extensao == 'jpeg') {
                if (!(\imagejpeg($img, $destino, $qualidade))) {
                    throw new ErrorException("Não foi possivel criar o arquivo " . \basename($destino));
                }
            } elseif ($extensao == 'gif') {
                if (!(\imagegif($img, $destino))) {
                    throw new ErrorException("Não foi possivel criar o arquivo " . \basename($destino));
                }
            } elseif ($extensao == 'png') {
                if (!(\imagepng($img, $destino))) {
                    throw new ErrorException("Não foi possivel criar o arquivo " . \basename($destino));
                }
            }
        } else {
            throw new ErrorException("Não foi possivel gerar o arquivo");
        }
    }

}
