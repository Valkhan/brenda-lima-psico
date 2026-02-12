<?php
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
define('EMAIL_DESTINO', 'contato@brendalima.com.br'); // ALTERE PARA O EMAIL DA BRENDA
define('EMAIL_ASSUNTO', 'Novo Pré-Agendamento - Site');
define('EMAIL_REMETENTE', 'noreply@brendalima.com.br'); // ALTERE PARA UM EMAIL VÁLIDO DO DOMÍNIO

// Função para sanitizar dados
function sanitizar($dado) {
    return htmlspecialchars(strip_tags(trim($dado)), ENT_QUOTES, 'UTF-8');
}

// Função para validar email
function validarEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Função para validar telefone brasileiro
function validarTelefone($telefone) {
    $telefone = preg_replace('/[^0-9]/', '', $telefone);
    return strlen($telefone) >= 10 && strlen($telefone) <= 11;
}

// Capturar e validar dados do formulário
$erros = [];

$nome = isset($_POST['nome']) ? sanitizar($_POST['nome']) : '';
if (empty($nome) || strlen($nome) < 3) {
    $erros[] = 'Nome inválido ou muito curto';
}

$telefone = isset($_POST['telefone']) ? sanitizar($_POST['telefone']) : '';
if (empty($telefone) || !validarTelefone($telefone)) {
    $erros[] = 'Telefone inválido';
}

$email = isset($_POST['email']) ? sanitizar($_POST['email']) : '';
if (empty($email) || !validarEmail($email)) {
    $erros[] = 'Email inválido';
}

$tipoAtendimento = isset($_POST['tipoAtendimento']) ? sanitizar($_POST['tipoAtendimento']) : '';
if (!in_array($tipoAtendimento, ['adulto', 'infantil'])) {
    $erros[] = 'Tipo de atendimento inválido';
}

$idadePaciente = isset($_POST['idadePaciente']) ? intval($_POST['idadePaciente']) : 0;
if ($idadePaciente < 0 || $idadePaciente > 120) {
    $erros[] = 'Idade inválida';
}

$motivoPrincipal = isset($_POST['motivoPrincipal']) ? sanitizar($_POST['motivoPrincipal']) : '';
if (empty($motivoPrincipal) || strlen($motivoPrincipal) < 10) {
    $erros[] = 'Motivo principal muito curto';
}

$possuiDiagnostico = isset($_POST['possuiDiagnostico']) ? sanitizar($_POST['possuiDiagnostico']) : '';
if (!in_array($possuiDiagnostico, ['sim', 'nao', 'parcial'])) {
    $erros[] = 'Opção de diagnóstico inválida';
}

$melhorHorario = isset($_POST['melhorHorario']) ? sanitizar($_POST['melhorHorario']) : '';
if (!in_array($melhorHorario, ['manha', 'tarde', 'noite'])) {
    $erros[] = 'Horário inválido';
}

$lgpd = isset($_POST['lgpd']) ? $_POST['lgpd'] === 'true' || $_POST['lgpd'] === '1' : false;
if (!$lgpd) {
    $erros[] = 'É necessário aceitar os termos da LGPD';
}

// Se houver erros, retornar
if (!empty($erros)) {
    http_response_code(400);
    echo json_encode([
        'sucesso' => false,
        'mensagem' => 'Erro na validação dos dados',
        'erros' => $erros
    ]);
    exit;
}

// Traduzir valores para texto legível
$tipoAtendimentoTexto = $tipoAtendimento === 'adulto' ? 'Atendimento Adulto' : 'Atendimento Infantil';

$diagnosticoTexto = [
    'sim' => 'Sim',
    'nao' => 'Não',
    'parcial' => 'Parcial/Em investigação'
][$possuiDiagnostico];

$horarioTexto = [
    'manha' => 'Manhã (8h-12h)',
    'tarde' => 'Tarde (12h-18h)',
    'noite' => 'Noite (18h-21h)'
][$melhorHorario];

// Criar corpo do email em HTML
$corpoHtml = "
<!DOCTYPE html>
<html lang='pt-BR'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif;
            background-color: #FAF7F5;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #FFFFFF;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #8B2635 0%, #6B1F2A 100%);
            color: #FFFFFF;
            padding: 40px 30px;
            text-align: center;
        }
        .header h1 {
            font-size: 28px;
            margin-bottom: 10px;
            font-weight: 600;
        }
        .header p {
            font-size: 16px;
            opacity: 0.9;
        }
        .content {
            padding: 40px 30px;
        }
        .info-block {
            margin-bottom: 30px;
        }
        .info-label {
            font-size: 12px;
            text-transform: uppercase;
            color: #6B6B6B;
            letter-spacing: 1px;
            margin-bottom: 5px;
            font-weight: 600;
        }
        .info-value {
            font-size: 16px;
            color: #3A3A3A;
            line-height: 1.6;
            padding: 12px;
            background-color: #FAF7F5;
            border-radius: 8px;
            border-left: 3px solid #8B2635;
        }
        .highlight {
            background-color: #F5F1ED;
            padding: 20px;
            border-radius: 10px;
            margin: 20px 0;
            border: 2px solid #9BA89B;
        }
        .highlight p {
            color: #3A3A3A;
            line-height: 1.6;
            font-size: 15px;
        }
        .footer {
            background-color: #F5F1ED;
            padding: 25px 30px;
            text-align: center;
            color: #6B6B6B;
            font-size: 13px;
        }
        .button {
            display: inline-block;
            background-color: #25D366;
            color: #FFFFFF;
            padding: 15px 30px;
            text-decoration: none;
            border-radius: 30px;
            margin: 20px 0;
            font-weight: 600;
            font-size: 15px;
        }
        .divider {
            height: 1px;
            background-color: #F5F1ED;
            margin: 30px 0;
        }
        .timestamp {
            font-size: 12px;
            color: #9BA89B;
            font-style: italic;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class='container'>
        <div class='header'>
            <h1>✨ Novo Pré-Agendamento</h1>
            <p>Solicitação recebida do site</p>
        </div>
        
        <div class='content'>
            <div class='info-block'>
                <div class='info-label'>Nome Completo</div>
                <div class='info-value'><strong>{$nome}</strong></div>
            </div>
            
            <div class='info-block'>
                <div class='info-label'>Contato</div>
                <div class='info-value'>
                    📱 <strong>{$telefone}</strong><br>
                    📧 {$email}
                </div>
            </div>
            
            <div class='divider'></div>
            
            <div class='info-block'>
                <div class='info-label'>Tipo de Atendimento</div>
                <div class='info-value'>{$tipoAtendimentoTexto}</div>
            </div>
            
            <div class='info-block'>
                <div class='info-label'>Idade do Paciente</div>
                <div class='info-value'>{$idadePaciente} anos</div>
            </div>
            
            <div class='highlight'>
                <div class='info-label'>Motivo Principal da Procura</div>
                <p>{$motivoPrincipal}</p>
            </div>
            
            <div class='info-block'>
                <div class='info-label'>Possui Diagnóstico Prévio?</div>
                <div class='info-value'>{$diagnosticoTexto}</div>
            </div>
            
            <div class='info-block'>
                <div class='info-label'>Melhor Horário para Contato</div>
                <div class='info-value'>{$horarioTexto}</div>
            </div>
            
            <div class='divider'></div>
            
            <div style='text-align: center;'>
                <a href='https://wa.me/55{$telefone}' class='button'>
                    💬 Responder via WhatsApp
                </a>
            </div>
            
            <p class='timestamp'>Recebido em: " . date('d/m/Y \à\s H:i:s') . "</p>
        </div>
        
        <div class='footer'>
            <p><strong>Brenda Lima</strong> | Neuropsicóloga & Psicóloga Comportamental</p>
            <p style='margin-top: 10px;'>Este é um email automático do sistema de pré-agendamento.</p>
        </div>
    </div>
</body>
</html>
";

// Criar versão em texto simples
$corpoTexto = "
===========================================
  NOVO PRÉ-AGENDAMENTO - SITE
===========================================

DADOS PESSOAIS
--------------
Nome: {$nome}
Telefone: {$telefone}
Email: {$email}

INFORMAÇÕES DO ATENDIMENTO
--------------------------
Tipo de Atendimento: {$tipoAtendimentoTexto}
Idade do Paciente: {$idadePaciente} anos

MOTIVO PRINCIPAL
----------------
{$motivoPrincipal}

INFORMAÇÕES ADICIONAIS
----------------------
Possui Diagnóstico: {$diagnosticoTexto}
Melhor Horário: {$horarioTexto}

Recebido em: " . date('d/m/Y \à\s H:i:s') . "

---
Brenda Lima | Neuropsicóloga
https://wa.me/55{$telefone}
";

// Configurar cabeçalhos do email
$headers = [
    'MIME-Version: 1.0',
    'Content-Type: text/html; charset=UTF-8',
    'From: ' . EMAIL_REMETENTE,
    'Reply-To: ' . $email,
    'X-Mailer: PHP/' . phpversion(),
    'X-Priority: 1',
    'Importance: High'
];

// Tentar enviar o email
$emailEnviado = mail(
    EMAIL_DESTINO,
    EMAIL_ASSUNTO,
    $corpoHtml,
    implode("\r\n", $headers)
);

// Registrar em log (opcional)
$logData = [
    'timestamp' => date('Y-m-d H:i:s'),
    'nome' => $nome,
    'email' => $email,
    'telefone' => $telefone,
    'tipo' => $tipoAtendimento,
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
