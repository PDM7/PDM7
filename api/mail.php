<?php
mb_internal_encoding('UTF-8');
// Função para carregar o arquivo .env
function loadEnv($file) {
    if (!file_exists($file)) {
        throw new Exception('Arquivo .env não encontrado.');
    }

    $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        list($key, $value) = explode('=', $line, 2);
        putenv(trim("{$key}={$value}"));
    }
}

// Autoload do PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'libs/PHPMailer/src/Exception.php';
require 'libs/PHPMailer/src/PHPMailer.php';
require 'libs/PHPMailer/src/SMTP.php';

// Carrega .env
loadEnv(__DIR__ . '/dados.env');

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");  
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With"); 
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type");
    http_response_code(200);
    exit();
}


function enviarEmail($data) {
    $smtpUsername = getenv('SMTP_USERNAME');
    $smtpPassword = getenv('SMTP_PASSWORD');

    if (!$data || !isset($data['respostas']) || !isset($data['pontuacoes'])) {
        return ['error' => 'Dados inválidos recebidos'];
    }

    $respostas = $data['respostas'];
    $pontuacoes = $data['pontuacoes'];
    $scoreAnsiedade = $pontuacoes['Ansiedade'];

    if ($scoreAnsiedade <= 12) {
        $nivelAnsiedade = 'Ansiedade Leve';
        $mensagemAnsiedade = 'A ansiedade leve é uma sensação natural...';
        $classeCss = 'result-good';
    } elseif ($scoreAnsiedade <= 25) {
        $nivelAnsiedade = 'Sinais de Alerta para Ansiedade';
        $mensagemAnsiedade = 'Quando a ansiedade dura muito tempo...';
        $classeCss = 'result-warning';
    } else {
        $nivelAnsiedade = 'Requer atenção para a ansiedade';
        $mensagemAnsiedade = 'Se a ansiedade começar a atrapalhar sua rotina...';
        $classeCss = 'result-alert';
    }

    $html = '<html><body>';
    $html .= '<h1>Resultado da Avaliação de Ansiedade Acadêmica</h1>';
    $html .= "<div class=\"$classeCss\"><strong>$nivelAnsiedade</strong><p>$mensagemAnsiedade</p></div>";
    $html .= "<p>Pontuação: <strong>$scoreAnsiedade</strong></p>";
    $html .= '<table border="1" cellpadding="5" cellspacing="0"><tr><th>Pergunta</th><th>Resposta</th><th>Pontuação</th></tr>';

    foreach ($respostas as $resposta) {
        $html .= "<tr><td>{$resposta['pergunta']}</td><td>{$resposta['resposta']}</td><td>{$resposta['score']}</td></tr>";
    }

    $html .= '</table>';
    $html .= '<p><small>Email automático, não responda.</small></p>';
    $html .= '</body></html>';
    $html = mb_convert_encoding($html, 'UTF-8', 'auto');

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->CharSet = 'UTF-8';
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = $smtpUsername;
        $mail->Password = $smtpPassword;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('colmeiapdm7@gmail.com', 'Sistema de Avaliação');
        $mail->addAddress('colmeiapdm7@gmail.com', 'Retorno');

        $mail->isHTML(true);
        $mail->Subject = 'Resultado da Avaliação de Ansiedade Acadêmica';
        $mail->Body = $html;
        $mail->AltBody = strip_tags($html);

        $mail->send();
        return ['success' => 'Email enviado com sucesso'];
    } catch (Exception $e) {
        return ['error' => "Erro ao enviar o email. Mailer Error: {$mail->ErrorInfo}"];
    }
}

// Se for via POST normal
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode($_POST['data'] ?? '', true);
    echo json_encode(enviarEmail($data));
}
?>