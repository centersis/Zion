<?php

namespace Zion\Form;

interface FilterableInput
{
    const EQUAL        = 'Equal';
    const GREATER_THAN = 'GreaterThan';
    const LESSER_THAN  = 'LesserThan';
    const LIKE         = 'Like';

    /**
     * Retorna o tipo do filtro (EQUAL, GREATER_THAN, LESSER_THAN, LIKE...)
     *
     * @return string
     */
    public function getCategoriaFiltro();

    /**
     * Seta o tipo de filtro de um input
     *
     * @return mixed
     */
    public function setCategoriaFiltro($categoria);
}

