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

use Zion\Validacao\Texto;
use Zion\Validacao\Data;
use Zion\Validacao\Numero;
use Zion\Validacao\Geral;

namespace Zion\Validacao;

class Valida
{

    private static $instancia;

    private function __construct()
    {
        
    }

    public static function instancia()
    {

        if (!isset(self::$instancia)) {
            self::$instancia = new self;
        }

        return self::$instancia;
    }

    /**
     * Valida::texto()
     * Retorna uma instância da classe de tratamento de Strings. Texto()
     * 
     * @return \Zion\Validacao\Texto
     */
    public function texto()
    {
        return Texto::instancia();
    }

    /**
     * Valida::data()
     * Retorna uma instância da classe de tratamento de Datas. Data()
     * 
     * @return \Zion\Validacao\Data
     */
    public function data()
    {
        return Data::instancia();
    }

    /**
     * Valida::numero()
     * Retorna uma instância da classe de tratamento de Float. Numero()
     * 
     * @return \Zion\Validacao\Numero
     */
    public function numero()
    {
        return Numero::instancia();
    }

    /**
     * Valida::geral()
     * Retorna uma instância da classe de tratamento de inputs especias. Geral()
     * 
     * @return \Zion\Validacao\Geral
     */
    public function geral()
    {
        return Geral::instancia();
    }
}
