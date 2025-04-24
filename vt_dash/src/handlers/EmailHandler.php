<?php

namespace src\handlers;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailHandler
{
    private $mailer;
    private $templatePath;
    private $vars = [];

    public function __construct()
    {
        $this->templatePath = __DIR__ . '/../../public/assets/templade/email.html';

        $this->mailer = new PHPMailer(true);
        $this->mailer->isSMTP();
        $this->mailer->Host = 'smtp.mailersend.net';
        $this->mailer->SMTPAuth = true;
        $this->mailer->Username = 'MS_YqSYsm@test-69oxl5ee2d2l785k.mlsender.net';
        $this->mailer->Password = 'mssp.KQTn8O2.jy7zpl9x3rpl5vx6.uq8BQJC'; 
        $this->mailer->SMTPSecure = 'tls'; // ou 'ssl' se mudar a porta
        $this->mailer->Port = 587; // ou 465 para SSL
        $this->mailer->CharSet = 'UTF-8';


        $this->mailer->setFrom('MS_YqSYsm@test-69oxl5ee2d2l785k.mlsender.net', 'Não Responda');
        $this->mailer->isHTML(true);
    }

    public function setVars(array $vars)
    {
        $this->vars = $vars;
    }

    public function send($to, $subject)
    {
        $internalVars = [
            'company.name'   => 'Minha Empresa',
            'company.info'   => 'Informações da empresa...',
            'company.footer' => 'Todos os direitos reservados © ' . date('Y')
        ];

        $html = file_get_contents($this->templatePath);

        // Junta tudo num array final de variáveis
        $allVars = array_merge($this->vars, $internalVars);

        foreach ($allVars as $key => $value) {
            $html = str_replace('{{' . $key . '}}', $value, $html);
        }

        try {
            $this->mailer->addAddress($to);
            $this->mailer->Subject = $subject;
            $this->mailer->Body = $html;

            $this->mailer->send();
            return true;
        } catch (Exception $e) {
            return 'Erro: ' . $this->mailer->ErrorInfo;
        }
    }
}
