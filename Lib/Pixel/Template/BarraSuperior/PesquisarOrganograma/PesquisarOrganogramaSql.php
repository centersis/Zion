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

namespace Pixel\Template\BarraSuperior\PesquisarOrganograma;

class PesquisarOrganogramaSql
{
    
    private $con;
    private $util;

    public function __construct()
    {

        $this->con = \Zion\Banco\Conexao::conectar();

    }     

    public function getDadosOrganograma($cod)
    {
        
        $qb = $this->con->link()->createQueryBuilder();
        
        $qb->select('*')
           ->from('organograma', 'a')
           ->where($qb->expr()->eq('a.organogramaCod', ':organogramaCod'))
           ->setParameter('organogramaCod', $cod);

        return $qb;        
/*        
        return "SELECT *
                  FROM  organograma
                 WHERE  organogramaCod = ".$cod;
 * 
 */
    }

    public function getDadosUsuario()
    {
        
        $qb = $this->con->link()->createQueryBuilder();
        
        $qb->select('*')
           ->from('_usuario', 'a')
           ->where($qb->expr()->eq('a.usuarioCod', ':usuarioCod'))
           ->setParameter('usuarioCod', $_SESSION['usuarioCod']);

        return $qb;  
/*        
        return "SELECT *
                  FROM  _usuario
                 WHERE  usuarioCod = ".$cod;
 * 
 */
    }    

}
