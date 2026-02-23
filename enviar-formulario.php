<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

// Configurações de segurança
header('Content-Type: application/json; charset=utf-8');
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');

// Apenas aceitar requisições POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['sucesso' => false, 'mensagem' => 'Método não permitido']);
    exit;
}

// Configurações de email
define('EMAIL_DESTINO', 'cadu17052006@gmail.com'); // Email de destino
define('EMAIL_ASSUNTO', 'Novo Pré-Agendamento - Site');

// Função para sanitizar dados
function sanitizar($dado) {
    return htmlspecialchars(strip_tags(trim($dado)), ENT_QUOTES, 'UTF-8');
}

// Função para validar email
function validarEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Função para enviar email via PHPMailer - RETORNA true/false
function enviarEmailPHPMailer($corpoHtml, $corpoTexto, $nome, $emailCliente) {
    $mail = new PHPMailer(true);

    try {
        // Configurações SMTP
        $mail->isSMTP();
        $mail->Host       = 'srv40.prodns.com.br';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'noreply@clinicabrendalima.com.br';
        $mail->Password   = '}grsY}2yV(m&';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = 465;
        $mail->CharSet    = 'UTF-8';

        // Remetente e Destinatário
        $mail->setFrom('noreply@clinicabrendalima.com.br', 'Site Psicóloga Brenda Lima');
        $mail->addAddress(EMAIL_DESTINO);
        $mail->addReplyTo($emailCliente, $nome);

        // Conteúdo do E-mail
        $mail->isHTML(true);
        $mail->Subject = EMAIL_ASSUNTO . ": $nome";
        $mail->Body    = $corpoHtml;
        $mail->AltBody = $corpoTexto;

        $mail->send();
        return true;

    } catch (Exception $e) {
        error_log("Erro PHPMailer: " . $mail->ErrorInfo);
        return false;
    }
}

// Função para validar telefone brasileiro
function validarTelefone($telefone): ?string {
    $telefone = preg_replace('/[^0-9]/', '', $telefone);
    if (strlen($telefone) >= 10 && strlen($telefone) <= 11) {
        return $telefone;
    } 

    return null;
}

// Capturar e validar dados do formulário
$erros = [];

$nome = isset($_POST['nome']) ? sanitizar($_POST['nome']) : '';
if (empty($nome) || strlen($nome) < 3) {
    $erros[] = 'Nome inválido ou muito curto';
}

$telefone = isset($_POST['telefone']) ? sanitizar($_POST['telefone']) : '';
$telefone = validarTelefone($telefone);
if (empty($telefone)) {
    $erros[] = 'Telefone inválido';
}

$email = isset($_POST['email']) ? sanitizar($_POST['email']) : '';
if (empty($email) || !validarEmail($email)) {
    $erros[] = 'Email inválido';
}

// CAMPOS ALINHADOS COM O FORMULÁRIO HTML
$idade = isset($_POST['idade']) ? intval($_POST['idade']) : 0;
if ($idade < 0 || $idade > 120) {
    $erros[] = 'Idade inválida';
}

$jaFezTerapia = isset($_POST['jaFezTerapia']) ? sanitizar($_POST['jaFezTerapia']) : '';
if (!in_array($jaFezTerapia, ['sim', 'nao'])) {
    $erros[] = 'Campo "Já fez terapia antes" inválido';
}

$possuiDiagnostico = isset($_POST['possuiDiagnostico']) ? sanitizar($_POST['possuiDiagnostico']) : '';
if (!in_array($possuiDiagnostico, ['sim', 'nao'])) {
    $erros[] = 'Opção de diagnóstico inválida';
}

$qualDiagnostico = isset($_POST['qualDiagnostico']) ? sanitizar($_POST['qualDiagnostico']) : '';

$motivoBusca = isset($_POST['motivoBusca']) ? sanitizar($_POST['motivoBusca']) : '';
if (empty($motivoBusca) || strlen($motivoBusca) < 5) {
    $erros[] = 'Motivo da busca muito curto';
}

$disponibilidade = isset($_POST['disponibilidade']) ? sanitizar($_POST['disponibilidade']) : '';
if (empty($disponibilidade) || strlen($disponibilidade) < 2) {
    $erros[] = 'Disponibilidade de horário inválida';
}

$lgpd = isset($_POST['lgpd']) ? ($_POST['lgpd'] === 'on' || $_POST['lgpd'] === 'true' || $_POST['lgpd'] === '1') : false;
if (!$lgpd) {
    $erros[] = 'É necessário aceitar os termos da LGPD';
}

// Se houver erros, retornar
if (!empty($erros)) {
    http_response_code(400);
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Erro na validação dos dados: ' . implode(', ', $erros),
        'erros' => $erros
    ]);
    exit;
}

// Traduzir valores para texto legível
$jaFezTerapiaTexto = $jaFezTerapia === 'sim' ? 'Sim' : 'Não';
$diagnosticoTexto = $possuiDiagnostico === 'sim' ? 'Sim' : 'Não';
$diagnosticoCompleto = $diagnosticoTexto . (!empty($qualDiagnostico) ? " - {$qualDiagnostico}" : "");
$dataHora = date('d/m/Y \à\s H:i:s');

// Função para carregar e processar template
function carregarTemplate($arquivo, $variaveis) {
    $template = file_get_contents(__DIR__ . '/templates/' . $arquivo);
    if ($template === false) {
        return false;
    }
    foreach ($variaveis as $chave => $valor) {
        $template = str_replace('{{' . $chave . '}}', $valor, $template);
    }
    return $template;
}

// Variáveis para substituição no template
$variaveis = [
    'nome' => $nome,
    'telefone' => $telefone,
    'email' => $email,
    'idade' => $idade,
    'jaFezTerapiaTexto' => $jaFezTerapiaTexto,
    'diagnosticoCompleto' => $diagnosticoCompleto,
    'motivoBusca' => $motivoBusca,
    'disponibilidade' => $disponibilidade,
    'dataHora' => $dataHora
];

// Carregar templates de email
$corpoHtml = carregarTemplate('email-template.html', $variaveis);
$corpoTexto = carregarTemplate('email-template.txt', $variaveis);

// Se falhar ao carregar templates, retornar erro
if ($corpoHtml === false || $corpoTexto === false) {
    http_response_code(500);
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Erro interno ao processar templates de email.'
    ]);
    exit;
}

// Tentar enviar o email usando PHPMailer
$emailEnviado = enviarEmailPHPMailer($corpoHtml, $corpoTexto, $nome, $email);


// Registrar em log (opcional)
$logData = [
    'timestamp' => date('Y-m-d H:i:s'),
    'nome' => $nome,
    'email' => $email,
    'telefone' => $telefone,
    'idade' => $idade,
    'email_enviado' => $emailEnviado
];

// Salvar em arquivo de log
$logFile = __DIR__ . '/logs/contatos.log';
if (!file_exists(dirname($logFile))) {
    mkdir(dirname($logFile), 0755, true);
}
file_put_contents($logFile, json_encode($logData) . "\n", FILE_APPEND);

// Responder ao cliente
if ($emailEnviado) {
    http_response_code(200);
    echo json_encode([
        'sucesso' => true,
        'mensagem' => 'Pré-agendamento enviado com sucesso! Entrarei em contato em breve.',
        'whatsapp' => "https://wa.me/5511990186911?text=" . urlencode("Olá Brenda! Acabei de preencher o formulário de pré-agendamento no site. Meu nome é {$nome}.")
    ]);
} else {
    http_response_code(500);
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Erro ao enviar email. Por favor, entre em contato via WhatsApp.',
        'whatsapp' => "https://wa.me/5511990186911?text=" . urlencode("Olá! Gostaria de agendar uma consulta.")
    ]);
}
?>
