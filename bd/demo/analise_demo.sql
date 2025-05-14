--insere tres registros de demonstracao, note a release "DEMO"
INSERT INTO public.analise (
        id,
        qid,
        classe,
        response,
        assign,
        payload,
        release,
        resume
    )
VALUES (
        '123e4567-e89b-12d3-a456-426614174000',
        'TESTE123',
        'Ciencia da Computacao 1 Periodo',
        '1232030013112 19',
        'usuario@demonstracao1',
        '{"curso": "Ciencia da Computacao", "ano": "1 Periodo", "qid": "respostas", "1232030013112", "pontuacao": "19", "avaliacao": "medio"]}',
        'DEMO',
        'medio'
    ),
    (
        '223e4567-e89b-12d3-a456-426614174001',
        'TESTE123',
        'Ciencia da Computacao 1 Periodo',
        '1222000010101 10',
        'usuario@demonstracao2',
        '{"curso": "Ciencia da Computacao", "ano": "1 Periodo", "qid": "respostas", "1222000010101", "pontuacao": "10", "avaliacao": "baixo"]}',
        'DEMO',
        'baixo'
    ),
    (
        '323e4567-e89b-12d3-a456-426614174002',
        'TESTE123',
        'Ciencia da Computacao 1 Periodo',
        '1232132313222 30',
        'usuario@demonstracao3',
        '{"curso": "Ciencia da Computacao", "ano": "1 Periodo", "qid": "respostas", "1232132313222", "pontuacao": "30", "avaliacao": "alto"]}',
        'DEMO',
        'alto'
    );