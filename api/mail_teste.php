<?php
require 'mail.php';

$data = [
    'respostas' => [
        ['pergunta' => 'Você se sente nervoso antes de provas?', 'resposta' => 'Sim', 'score' => 3],
        ['pergunta' => 'Você tem dificuldade para dormir?', 'resposta' => 'Às vezes', 'score' => 2]
    ],
    'pontuacoes' => [
        'Ansiedade' => 5
    ]
];

echo json_encode(enviarEmail($data));
