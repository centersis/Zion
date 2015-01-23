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

/** Loader 
 * @author Feliphe Bueno - feliphezion@gmail.com
 * @since 08/09/2014
 * @version 1.0
 * @copyright 2014
 * 
 * Carregamento automático de classes, baseado no Autoload Component do Symfony
 * de acordo com as diretrizes PSR-0 e PSR-4.
 * 
 */

namespace Zion\ClassLoader;

class Loader
{
    private $nameSpaces;

    public function __construct()
    {
        $this->nameSpaces = [];
    }

    /**
     * Registra os namespaces setados em $nameSpaces
     * @return object
     */
    public function inicio()
    {
        require_once "UniversalClassLoader.php";

        $loader = new \Zion\ClassLoader\UniversalClassLoader();

        foreach ($this->nameSpaces as $name => $caminho) {

            $loader->registerNamespace($name, $caminho);
        }

        $loader->register();
    }

    /**
     * Seta nome e caminho para um namespace
     * @param string $name
     * @param string $caminho
     * @return \Zion\ClassLoader\Loader
     */
    public function setNameSpaces($name, $caminho)
    {
        $this->nameSpaces[$name] = $caminho;
        return $this;
    }

}
