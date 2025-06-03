<?php
mb_internal_encoding('UTF-8');
// Autoload do PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'libs/PHPMailer/src/Exception.php';
require 'libs/PHPMailer/src/PHPMailer.php';
require 'libs/PHPMailer/src/SMTP.php';
require_once './repository.php';

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

    // $repo = new Repository();
    // $dadosPerguntas = $repo->getPerguntasERespostas();

    $respostas = $data['respostas'];
    $pontuacoes = $data['pontuacoes'];
    $scoreAnsiedade = $pontuacoes['Ansiedade'];
    $category = $data['category'] ?? 'Ansiedade';

    if($category == 'Autoestima'){
        $nivelAnsiedade = 'Obrigado por responder!';
        $mensagemAnsiedade = 'Lembre-se: a pessoa mais importante da sua vida é você. Cuide-se, respeite seus limites e celebre cada conquista. Este quiz foi só o começo. Siga se conhecendo e se valorizando, você merece se sentir bem todos os dias.';
        $classeCss = 'result-generic';
    }else if($category == 'Ansiedade'){
        if ($scoreAnsiedade <= 12) {
        $nivelAnsiedade = 'Ansiedade Leve';
        $mensagemAnsiedade = 'A ansiedade leve é uma sensação natural de nervosismo ou preocupação que acontece em situações como provas, entrevistas ou mudanças importantes na vida. Ela pode causar um pouco de inquietação ou preocupação, mas não atrapalha as atividades diárias, inclusive é saudável e ajuda nos processos psíquicos.';
        $classeCss = 'result-good';
    } elseif ($scoreAnsiedade <= 25) {
        $nivelAnsiedade = 'Sinais de Alerta para Ansiedade';
        $mensagemAnsiedade = 'Quando a ansiedade dura muito tempo ou começa a afetar suas atividades diárias é um sinal de que algo mais sério pode estar acontecendo. Sintomas como dificuldade para dormir, preocupação constante, tensão no corpo, alterações no apetite ou libido, baixa concentração ou medo excessivo são sinais de alerta.';
        $classeCss = 'result-warning';
    } else {
        $nivelAnsiedade = 'Requer atenção para a ansiedade';
        $mensagemAnsiedade = 'Se a ansiedade começar a atrapalhar sua rotina ou se os sintomas citados no sinal de alerta persistirem, é importante buscar a ajuda profissional. A psicoterapia e em alguns casos medicamentos, podem ajudar a controlar a ansiedade e melhorar sua qualidade de vida. Resistir a buscar ajuda, pode trazer sérios prejuízos. Cuidar da saúde mental não é fraqueza, é vida.';
        $classeCss = 'result-alert';
    }
}

    $html = '<html><body>';
    $html .= '<h1>Resultado da Avaliação de Ansiedade Acadêmica</h1>';
    $html .= "<div style=\"padding: 20px; border-left: 5px solid; margin-bottom: 20px;\"><strong>$nivelAnsiedade</strong><p>$mensagemAnsiedade</p></div>";
    $html .= "<p>Pontuação: <strong>$scoreAnsiedade</strong></p>";
    $html .= '<table border="1" cellpadding="5" cellspacing="0" style="width: 100%; margin-bottom: 20px;"><tr><th>Pergunta</th><th>Resposta</th><th>Pontuação</th></tr>';

    foreach ($respostas as $resposta) {
        $html .= "<tr><td>{$resposta['pergunta']}</td><td>{$resposta['resposta']}</td><td>{$resposta['score']}</td></tr>";
    }

    $html .= '</table>';
    $html .= '<p><small>Este e-mail foi enviado automaticamente como resposta ao seu teste. Não responda este e-mail.</small></p>';
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

        $mail->setFrom('colmeiapdm7@gmail.com', 'Sistema de Avaliação de '."$category".'');
        
        // Lógica modificada para destinatários
        if (isset($data['email']) && filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            // Se tem e-mail do usuário, envia apenas para ele
            $mail->addAddress($data['email']);
            $destinatario = $data['email'];
        } else {
            // Se não tem e-mail do usuário, envia apenas para você, com data
            $mail->addAddress('colmeiapdm7@gmail.com', 'Retorno ' . date('d/m/Y H:i:s'));
            $destinatario = 'colmeiapdm7@gmail.com';
        }

        $mail->isHTML(true);
        $mail->Subject = isset($data['email']) ? 'Seu Resultado da Avaliação' : 'Novo Resultado de Avaliação';
        $mail->Body = $html;
        $mail->AltBody = strip_tags($html);

        $mail->send();
        
        // Retorno diferente dependendo de quem recebeu
        if (isset($data['email'])) {
            return ['success' => 'E-mail enviado com sucesso para o usuário'];
        } else {
            return ['success' => 'Relatório enviado para administração'];
        }
    } catch (Exception $e) {
        return ['error' => "Erro ao enviar o e-mail. Mailer Error: {$mail->ErrorInfo}"];
    }
}

// Se for via POST normal
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        echo json_encode(['error' => 'Dados JSON inválidos']);
        exit;
    }
    
    echo json_encode(enviarEmail($data));
}
?>