<?php

/**
 *
 *    Sappiens Framework
 *    Copyright (C) 2014, BRA Consultoria
 *
 *    Website do autor: www.braconsultoria.com.br/sappiens
 *    Email do autor: sappiens@braconsultoria.com.br
 *
 *    Website do projeto, equipe e documentação: www.sappiens.com.br
 *   
 *    Este programa é software livre; você pode redistribuí-lo e/ou
 *    modificá-lo sob os termos da Licença Pública Geral GNU, conforme
 *    publicada pela Free Software Foundation, versão 2.
 *
 *    Este programa é distribuído na expectativa de ser útil, mas SEM
 *    QUALQUER GARANTIA; sem mesmo a garantia implícita de
 *    COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM
 *    PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais
 *    detalhes.
 * 
 *    Você deve ter recebido uma cópia da Licença Pública Geral GNU
 *    junto com este programa; se não, escreva para a Free Software
 *    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *    02111-1307, USA.
 *
 *    Cópias da licença disponíveis em /Sappiens/_doc/licenca
 *
 */

namespace Zion\Arquivo;

class ManipulaImagem extends ManipulaArquivo
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Verifica se as funções nescessárias para manipulação básica de imagens 
     * estão disponíveis no servidor
     * @throws \Exception
     */
    public function vericaFuncoesDeImagem()
    {
        $tI = "Função de manipulação "; //Texto Inicial
        $tF = " não existe, entre em contato com o administrador do sistema."; //Texto Final

        if (!\function_exists('getimagesize')) {
            throw new \Exception($tI . "getimagesize" . $tF);
        }

        if (!\function_exists('imagecreatefromjpeg')) {
            throw new \Exception($tI . "imagecreatefromjpeg" . $tF);
        }

        if (!\function_exists('imagecreatetruecolor')) {
            throw new \Exception($tI . "imagecreatetruecolor" . $tF);
        }

        if (!\function_exists('imagecolorallocate')) {
            throw new \Exception($tI . "imagecolorallocate" . $tF);
        }

        if (!\function_exists('imagecopyresampled')) {
            throw new \Exception($tI . "imagecopyresampled" . $tF);
        }

        if (!\function_exists('imagesy')) {
            throw new \Exception($tI . "imagesy" . $tF);
        }

        if (!\function_exists('imagesx')) {
            throw new \Exception($tI . "imagesx" . $tF);
        }

        if (!\function_exists('imagejpeg')) {
            throw new \Exception($tI . "imagejpeg" . $tF);
        }
    }

    /**
     * Retorna um array com a altura e a largura da imagem
     * @param link $imagem
     * @return array
     */
    public function dimensaoImagem($imagem)
    {
        $imgSize = \getimagesize($imagem);
        return ["L" => $imgSize[0], "A" => $imgSize[1]];
    }

    /**
     * Retorna um array com altura e largura proporcionais da imagem
     * @param int $tamanho
     * @param string $por A -> Altura ou L -> Largura
     * @param array $originais Altura e Largura Originais
     * @return array
     * @throws \Exception
     */
    public function proporcoesImagem($tamanho, $por, $originais)
    {
        if ($originais['A'] == 0 or $originais['L'] == 0) {
            throw new \Exception("Proporções do arquivo invalidas, certifique-se que o arquivo é mesmo uma imagem!");
        }

        switch ($por) {
            case "A":
                $calcula = ($tamanho * 100) / $originais['A'];
                $largura = ($originais['L'] * $calcula) / 100;
                return ["L" => $largura, "A" => $tamanho];

            case "L":
                $calcula = ($tamanho * 100) / $originais['L'];
                $altura = ($originais['A'] * $calcula) / 100;
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
     * @throws \Exception
     */
    public function uploadImagem($nomeImagem, $origem, $destino, $altura = 0, $largura = 0)
    {
        $postMax = \ini_get("post_max_size");
        $upMax = \ini_get("upload_max_filesize");

        //Menor Tamanho de Configuração
        $tMax = $postMax > $upMax ? $upMax : $postMax;

        //Verifica a integridade do arquivo
        if (!$this->arquivoExiste($origem)) {
            throw new \Exception("O Arquivo não foi carregado, certifique-se que o tamanho do arquivo não tenha ultrapassado " . $tMax . " pois, este tamanho é o maximo permitido pelo seu servidor.");
        }

        //Verifica se a pasta permite gravação
        if (!$this->permiteEscrita(\dirname($destino))) {
            throw new \Exception("A pasta onde você esta tentando gravar o arquivo não tem permissão de escrita, contate o administrador do sistema.$destino");
        }

        //Verifica se o arquivo ja existe
        if ($this->arquivoExiste($destino)) {
            //Se sim verifica se tem permissão para substitui-lo
            if (!$this->permiteEscrita($destino)) {
                throw new \Exception("Este arquivo já existe e você não tem permissão para substituí-lo.");
            }
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
        } elseif (!empty($altura) and ! empty($largura)) {

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
            $origem = @\imagecreatefrompng($origem);
        } else {
            throw new \Exception("Extensao inválida!");
        }

        if (!$origem) {
            throw new \Exception("Não foi possivel gerar o arquivo!");
        }

        $img = \imagecreatetruecolor($proporcao['L'], $proporcao['A']);

        if (\imagecopyresampled($img, $origem, 0, 0, 0, 0, $proporcao['L'], $proporcao['A'], \imagesx($origem), \imagesy($origem))) {
            if ($extensao == 'jpg' or $extensao == 'jpeg') {
                if (!(\imagejpeg($img, $destino, 100))) {
                    throw new \Exception("Não foi possivel criar o arquivo " . \basename($destino));
                }
            } elseif ($extensao == 'gif') {
                if (!(\imagegif($img, $destino))) {
                    throw new \Exception("Não foi possivel criar o arquivo " . \basename($destino));
                }
            } elseif ($extensao == 'png') {
                if (!(\imagepng($img, $destino))) {
                    throw new \Exception("Não foi possivel criar o arquivo " . \basename($destino));
                }
            }
        } else {
            throw new \Exception("Não foi possivel gerar o arquivo");
        }
    }

}
