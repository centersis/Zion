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

namespace Pixel\Twig;

use Zion\Arquivo\ManipulaDiretorio;

class Carregador
{

    private $twig;
    private $loader;
    private $caminhos;
    private $conf;
    private $dir;

    public function __construct($namespace = '')
    {
        $this->caminhos = [];
        $this->dir = new ManipulaDiretorio();

        if ($namespace) {
            $dirnamespace = $this->interpretaNamespace($namespace) . '/Tema/Vendor/' . \SIS_VENDOR_TEMPLATE . '/views';

            if ($this->dir->eDiretorio($dirnamespace)) {
                $this->caminhos[] = $this->interpretaNamespace($namespace) . '/Tema/Vendor/' . \SIS_VENDOR_TEMPLATE . '/views';
            }
        }

        $caminhoBase = \SIS_DIR_DEFAULT_BASE . 'Tema/Vendor/' . \SIS_VENDOR_TEMPLATE . '/views';
        $caminhoProjeto = \SIS_DIR_DEFAULT_BASE . 'Tema/Vendor/' . \SIS_VENDOR_TEMPLATE . '/views';

        if ($this->dir->eDiretorio($caminhoBase)) {
            $this->caminhos[] = $caminhoBase;
        }

        if ($this->dir->eDiretorio($caminhoProjeto)) {
            $this->caminhos[] = $caminhoProjeto;
        }

        $this->loader = new \Twig_Loader_Filesystem($this->caminhos);

        if (\SIS_RELEASE === 'Developer') {
            $this->conf['debug'] = true;
        } else {
            $this->conf['debug'] = false;
            $this->conf['cache'] = \SIS_DIR_BASE . 'Tema/Vendor/' . \SIS_VENDOR_TEMPLATE . '/cache';
        }

        $this->twig = new \Twig_Environment($this->loader, $this->conf);

        $urlBase = new \Twig_SimpleFunction('urlBase', function ($url) {
            return \SIS_URL_BASE . $url;
        });

        $urlBaseTema = new \Twig_SimpleFunction('urlBaseTema', function ($url) {
            return \SIS_URL_DEFAULT_BASE . 'Tema/Vendor/' . \SIS_VENDOR_TEMPLATE . '/' . $url;
        });

        $urlFramework = new \Twig_SimpleFunction('urlFramework', function ($url) {
            return \SIS_URL_FM_BASE . $url;
        });

        $this->twig->addFunction($urlBase);
        $this->twig->addFunction($urlBaseTema);
        $this->twig->addFunction($urlFramework);
    }

    public function twig()
    {
        return $this->twig;
    }

    public function loader()
    {
        return $this->loader;
    }

    public function render($caminho, $dados = [])
    {
        return $this->twig->render($caminho, $dados);
    }

    public function setCaminhoAntes($caminho)
    {
        $caminhoCompleto = \SIS_DIR_BASE . $caminho;

        \array_unshift($this->caminhos, $caminhoCompleto);

        $this->loader->prependPath($caminhoCompleto);
    }

    public function setCaminhoDepois($caminho)
    {
        $caminhoCompleto = \SIS_DIR_BASE . $caminho;

        $this->caminhos[] = $caminhoCompleto;

        $this->loader->addPath($caminhoCompleto);
    }

    private function interpretaNamespace($namespace)
    {
        if ($namespace !== '') {

            $caminho = \SIS_DIR_BASE . \str_replace(\SIS_ID_NAMESPACE_PROJETO . '\\', '', $namespace);

            return $this->dir->padronizaDiretorio($caminho, '/');
        }

        return $namespace;
    }

}
