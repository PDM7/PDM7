<?php
require 'mail.php';

$_SERVER['REQUEST_METHOD'] = 'POST';
$_POST['data'] = json_encode([
    'respostas' => [
        ['pergunta' => 'Você se sente nervoso antes de provas?', 'resposta' => 'Sim', 'score' => 3],
        ['pergunta' => 'Você tem dificuldade para dormir antes de avaliações?', 'resposta' => 'Às vezes', 'score' => 2]
    ],
    'pontuacoes' => [
        'Ansiedade' => 5
    ]
]);

