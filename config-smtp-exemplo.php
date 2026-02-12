<?php
/**
 * EXEMPLO DE CONFIGURAÇÃO COM PHPMAILER (SMTP)
 * 
 * Este arquivo mostra como configurar o envio via SMTP
 * usando PHPMailer. É mais confiável que a função mail().
 * 
 * INSTALAÇÃO:
 * composer require phpmailer/phpmailer
 * 
 * DEPOIS:
 * Substitua a seção de envio no enviar-formulario.php
 * pelo código mostrado aqui.
 */

// ============================================
// EXEMPLO 1: Gmail SMTP
// ============================================

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

function enviarEmailGmail($destinatario, $assunto, $corpoHtml, $corpoTexto) {
    $mail = new PHPMailer(true);

    try {
        // Configurações do servidor
        $mail->SMTPDebug = SMTP::DEBUG_OFF;                     // Desabilitar debug
        $mail->isSMTP();                                        // Usar SMTP
        $mail->Host       = 'smtp.gmail.com';                   // Servidor Gmail
        $mail->SMTPAuth   = true;                               // Autenticação
        $mail->Username   = 'seu-email@gmail.com';              // ⬅️ ALTERE
        $mail->Password   = 'sua-senha-de-app';                 // ⬅️ ALTERE (usar senha de app)
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;     // TLS
        $mail->Port       = 587;                                // Porta TLS
        $mail->CharSet    = 'UTF-8';

        // Remetente e destinatário
        $mail->setFrom('seu-email@gmail.com', 'Brenda Lima');
        $mail->addAddress($destinatario);
        $mail->addReplyTo('brenda@exemplo.com', 'Brenda Lima');

        // Conteúdo
        $mail->isHTML(true);
        $mail->Subject = $assunto;
        $mail->Body    = $corpoHtml;
        $mail->AltBody = $corpoTexto;

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Erro ao enviar email: {$mail->ErrorInfo}");
        return false;
    }
}

// ============================================
// EXEMPLO 2: SendGrid SMTP
// ============================================

function enviarEmailSendGrid($destinatario, $assunto, $corpoHtml, $corpoTexto) {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.sendgrid.net';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'apikey';                           // Sempre 'apikey'
        $mail->Password   = 'SUA_API_KEY_SENDGRID';             // ⬅️ ALTERE
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        $mail->CharSet    = 'UTF-8';

        $mail->setFrom('noreply@seudominio.com', 'Brenda Lima');
        $mail->addAddress($destinatario);

        $mail->isHTML(true);
        $mail->Subject = $assunto;
        $mail->Body    = $corpoHtml;
        $mail->AltBody = $corpoTexto;

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Erro SendGrid: {$mail->ErrorInfo}");
        return false;
    }
}

// ============================================
// EXEMPLO 3: Mailgun SMTP
// ============================================

function enviarEmailMailgun($destinatario, $assunto, $corpoHtml, $corpoTexto) {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.mailgun.org';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'postmaster@seudominio.com';        // ⬅️ ALTERE
        $mail->Password   = 'SUA_SENHA_MAILGUN';                // ⬅️ ALTERE
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        $mail->CharSet    = 'UTF-8';

        $mail->setFrom('noreply@seudominio.com', 'Brenda Lima');
        $mail->addAddress($destinatario);

        $mail->isHTML(true);
        $mail->Subject = $assunto;
        $mail->Body    = $corpoHtml;
        $mail->AltBody = $corpoTexto;

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Erro Mailgun: {$mail->ErrorInfo}");
        return false;
    }
}

// ============================================
// EXEMPLO 4: Office 365 SMTP
// ============================================

function enviarEmailOffice365($destinatario, $assunto, $corpoHtml, $corpoTexto) {
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.office365.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'seu-email@outlook.com';            // ⬅️ ALTERE
        $mail->Password   = 'sua-senha';                        // ⬅️ ALTERE
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;
        $mail->CharSet    = 'UTF-8';

        $mail->setFrom('seu-email@outlook.com', 'Brenda Lima');
        $mail->addAddress($destinatario);

        $mail->isHTML(true);
        $mail->Subject = $assunto;
        $mail->Body    = $corpoHtml;
        $mail->AltBody = $corpoTexto;

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Erro Office365: {$mail->ErrorInfo}");
        return false;
    }
}

// ============================================
// COMO USAR NO enviar-formulario.php
// ============================================

/*
Substitua estas linhas (aproximadamente linha 145):

    // Tentar enviar o email
    $emailEnviado = mail(
        EMAIL_DESTINO,
        EMAIL_ASSUNTO,
        $corpoHtml,
        implode("\r\n", $headers)
    );

POR:

    // Tentar enviar o email via SMTP
    $emailEnviado = enviarEmailGmail(
        EMAIL_DESTINO,
        EMAIL_ASSUNTO,
        $corpoHtml,
        $corpoTexto
    );

E adicione no topo do arquivo:

    require_once 'config-smtp.php';
*/

// ============================================
// DICAS IMPORTANTES
// ============================================

/*
1. GMAIL:
   - Use "Senhas de app" (não a senha normal)
   - Ative autenticação em 2 fatores
   - Gere senha em: https://myaccount.google.com/apppasswords

2. SENDGRID:
   - Gratuito até 100 emails/dia
   - Altamente confiável
   - Cadastro: https://sendgrid.com

3. MAILGUN:
   - Gratuito até 5000 emails/mês
   - Ótimo para desenvolvimento
   - Cadastro: https://www.mailgun.com

4. OFFICE 365:
   - Se já usa Office 365 corporativo
   - Requer licença ativa

5. SEGURANÇA:
   - NUNCA commite senhas no Git
   - Use variáveis de ambiente
   - Exemplo: getenv('SMTP_PASSWORD')

6. RATE LIMITING:
   - Gmail: ~500 emails/dia
   - SendGrid Free: 100 emails/dia
   - Mailgun Free: 5000 emails/mês
*/

?>
