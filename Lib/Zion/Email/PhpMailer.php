<?php/****    Sappiens Framework*    Copyright (C) 2014, BRA Consultoria**    Website do autor: www.braconsultoria.com.br/sappiens*    Email do autor: sappiens@braconsultoria.com.br**    Website do projeto, equipe e documentação: www.sappiens.com.br*   *    Este programa é software livre; você pode redistribuí-lo e/ou*    modificá-lo sob os termos da Licença Pública Geral GNU, conforme*    publicada pela Free Software Foundation, versão 2.**    Este programa é distribuído na expectativa de ser útil, mas SEM*    QUALQUER GARANTIA; sem mesmo a garantia implícita de*    COMERCIALIZAÇÃO ou de ADEQUAÇÃO A QUALQUER PROPÓSITO EM*    PARTICULAR. Consulte a Licença Pública Geral GNU para obter mais*    detalhes.* *    Você deve ter recebido uma cópia da Licença Pública Geral GNU*    junto com este programa; se não, escreva para a Free Software*    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA*    02111-1307, USA.**    Cópias da licença disponíveis em /Sappiens/_doc/licenca**//** * @author Pablo Vanni - pablovanni@gmail.com */namespace Zion\Email;class PhpMailer{    /**     * PhpMailer::enviarEmail()     *      * @param string $Email     * @param string $Assunto     * @param string $msg     * @return     */    public function enviarEmail($Email, $Assunto, $msg)    {        $fPHP = new FuncoesPHP();        if (!$fPHP->verificaEmail($Email))            throw new Exception("O Email '$Email' é Inválido!");        $mail = new PHPMailer();        $mail->IsSMTP();        $mail->Host = ConfigSIS::$CFG['HostEmail'];        $mail->SMTPAuth = true; // Usa autenticação SMTP? (opcional)        $mail->Username = ConfigSIS::$CFG['EmailPadrao']; // Usuário do servidor SMTP        $mail->Password = ConfigSIS::$CFG['SenhaEmail']; // Senha do servidor SMTP        $mail->From = ConfigSIS::$CFG['EmailPadrao']; // Seu e-mail        $mail->FromName = ConfigSIS::$CFG['EmailTag']; // Seu nome        $mail->AddAddress($Email, 'Usuário do Site');        $mail->IsHTML(true);        $mail->CharSet = 'UTF-8';        //$mail->SMTPDebug = 2;         if (ConfigSIS::$CFG['PortaEmail']) {            $mail->Port = ConfigSIS::$CFG['PortaEmail'];            $mail->SMTPSecure = "ssl";        }        $mail->Subject = $Assunto; // Assunto da mensagem        $mail->Body = $msg;        $Enviado = $mail->Send();        $mail->clearAllRecipients();        if (!$Enviado) {            throw new Exception('Não foi possivel enviar o e-mail, motivo: ' . $mail->ErrorInfo);        }    }    /**     * PhpMailer::enviarEmailGrupo()     *      * @param mixed $GrupoEmails     * @param mixed $Assunto     * @param mixed $msg     * @return     */    public function enviarEmailGrupo($GrupoEmails, $Assunto, $msg)    {        $fPHP = new FuncoesPHP();        $mail = new PHPMailer();        $mail->IsSMTP();        $mail->Host = ConfigSIS::$CFG['HostEmail'];        $mail->SMTPAuth = true; // Usa autenticação SMTP? (opcional)        $mail->Username = ConfigSIS::$CFG['EmailPadrao']; // Usuário do servidor SMTP        $mail->Password = ConfigSIS::$CFG['SenhaEmail']; // Senha do servidor SMTP        $mail->From = ConfigSIS::$CFG['EmailPadrao']; // Seu e-mail        $mail->FromName = ConfigSIS::$CFG['EmailTag']; // Seu nome                $mail->IsHTML(true);        $mail->CharSet = 'iso-8859-1';        if (ConfigSIS::$CFG['PortaEmail']) {            $mail->Port = ConfigSIS::$CFG['PortaEmail'];            $mail->SMTPSecure = "ssl";        }        $mail->Subject = $Assunto; // Assunto da mensagem        $mail->Body = $msg;        $TotalEnviados = 0;        foreach ($GrupoEmails as $Email) {            if ($fPHP->verificaEmail($Email)) {                $mail->AddAddress($Email, 'Usuário do Site');                $Enviado = $mail->Send();                $mail->clearAllRecipients();                if ($Enviado) {                    $TotalEnviados++;                }            }        }        return $TotalEnviados;    }    /**     * PhpMailer::teste()     *      * @return     */    public function teste()    {        return 'funcionei';    }}