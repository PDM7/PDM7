<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");  
header("Access-Control-Allow-Headers: Content-Type"); 


if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'libs/PHPMailer/src/Exception.php';
require 'libs/PHPMailer/src/PHPMailer.php';
require 'libs/PHPMailer/src/SMTP.php';

// Receber os dados JSON
$data = json_decode($_POST['data'] ?? '', true);

if (!$data || !isset($data['respostas']) || !isset($data['pontuacoes'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Dados inválidos recebidos']);
    exit;
}

$respostas = $data['respostas'];
$pontuacoes = $data['pontuacoes'];
$scoreAnsiedade = $pontuacoes['Ansiedade'];

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

$html = '
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultado da Avaliação de Ansiedade</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #4a6fa5;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 0 0 5px 5px;
        }
        .result-message {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        .result-good {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .result-warning {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeeba;
        }
        .result-alert {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .result-main-title {
            margin-top: 0;
            color: inherit;
        }
        .respostas-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .respostas-table th, .respostas-table td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }
        .respostas-table th {
            background-color: #f2f2f2;
        }
        .respostas-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .score-display {
            font-size: 18px;
            font-weight: bold;
            margin: 15px 0;
            text-align: center;
        }
        .footer {
            margin-top: 20px;
            font-size: 12px;
            text-align: center;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Resultado da Avaliação de Ansiedade Acadêmica</h1>
    </div>
    
    <div class="content">
        <div class="score-display">
            Pontuação Total de Ansiedade: '.$scoreAnsiedade.'
        </div>
        
        <div class="result-message '.$classeCss.'">
            <h3 class="result-main-title">'.$nivelAnsiedade.'</h3>
            <p>'.$mensagemAnsiedade.'</p>
        </div>
        
        <h3>Suas Respostas:</h3>
        <table class="respostas-table">
            <thead>
                <tr>
                    <th>Pergunta</th>
                    <th>Resposta</th>
                    <th>Pontuação</th>
                </tr>
            </thead>
            <tbody>';

foreach ($respostas as $resposta) {
    $html .= '
                <tr>
                    <td>'.$resposta['pergunta'].'</td>
                    <td>'.$resposta['resposta'].'</td>
                    <td>'.$resposta['score'].'</td>
                </tr>';
}

$html .= '
            </tbody>
        </table>
        
        <div class="footer">
            <p>Este é um email automático. Por favor, não responda diretamente a esta mensagem.</p>
            <p>Se você tiver alguma dúvida ou preocupação, entre em contato com o suporte.</p>
        </div>
    </div>
</body>
</html>';


$mail = new PHPMailer(true);

try {
    $mail = new PHPMailer(true);

    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com'; 
    $mail->SMTPAuth = true;
    $mail->Username = '';  
    $mail->Password = '';  
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;  
    $mail->Port = 587;  

    // Remetente e destinatário
    $mail->setFrom('colmeiapdm7@gmail.com', 'Sistema de Avaliação');
    $mail->addAddress('colmeiapdm7@email.com', 'Retorno'); 

    // Conteúdo do email
    $mail->isHTML(true);
    $mail->Subject = 'Resultado da Avaliação de Ansiedade Acadêmica';
    $mail->Body= $html;
    $mail->AltBody = strip_tags($html);

    // Enviar o e-mail
    $mail->send();
    echo json_encode(['success' => 'Email enviado com sucesso']);
} catch (Exception $e) {
    echo json_encode(['error' => "Erro ao enviar o email. Mailer Error: {$mail->ErrorInfo}"]);
}

?>