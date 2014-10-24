<?php

/**
 * \Zion\JQuery\JQuery()
 * 
 * @author The Sappiens Team
 * @copyright 2014
 * @version 2014
 * @access public
 */
 
namespace Zion\JQuery;

class JQuery
{
    /**
     * JQuery::ajax()
     * 
     * @return
     */
    public function ajax()
    {
        return new Ajax();
    }
}
