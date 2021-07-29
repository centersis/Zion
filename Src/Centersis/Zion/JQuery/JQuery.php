<?php

namespace Centersis\Zion\JQuery;

class JQuery
{

    /**
     * Inicia uma instancia de ajax
     * @return \Zion\JQuery\Ajax
     */
    public function ajax()
    {
        return new Ajax();
    }

}
