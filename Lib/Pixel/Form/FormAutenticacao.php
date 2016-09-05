<?php

namespace Pixel\Form;

class FormAutenticacao
{
    public static function validaSenha($form)
    {

        return $form->senha('validaSenhaUser', 'Sua senha de utilizador', true)
                        ->setMaximoCaracteres(30)
                        ->setMinimoCaracteres(6)
                        ->setValor($form->retornaValor('validaSenhaUser'))
                        ->setIconFA('fa-lock');
    }

}
