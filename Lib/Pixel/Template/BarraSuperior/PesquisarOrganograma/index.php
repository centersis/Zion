<?php

echo (new \Pixel\Template\BarraSuperior\PesquisarOrganograma\PesquisarOrganogramaController())->controle(\filter_input(\INPUT_GET, 'acao'));
