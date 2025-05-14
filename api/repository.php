<?php

class Repository {
    private $pdo;

    public function __construct() {
        $host = getenv('DB_HOST');
        $port = getenv('DB_PORT') ?: 6543;
        $db   = getenv('DB_NAME');
        $user = getenv('DB_USER');
        $pass = getenv('DB_PASS');
        $dsn = "pgsql:host=$host;port=$port;dbname=$db";

        try {
            $this->pdo = new PDO($dsn, $user, $pass);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die('Erro na conexão com o banco de dados: ' . $e->getMessage());
        }
    }
    
    public function dataHoraServidor() {
        try {
            $stmt = $this->pdo->query("SELECT NOW()");
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['now']; // formato: 2025-05-13 10:35:42
        } catch (PDOException $e) {
            return 'Erro ao obter data/hora: ' . $e->getMessage();
        }
    }

    public function inserirAnalise($data) {
        if (!$data || !isset($data['respostas']) || !isset($data['pontuacoes'])) {
            return ['error' => 'Dados inválidos para inserção'];
        }

        try {
            // Gerando um UUID manualmente (ou use uma função nativa se estiver disponível)
            $id = $this->gerarUuid();
            
            $qid     = $data['qid'] ?? 'QANAMNESE_ANSIEDADE';
            $classe  = $data['classe'] ?? 'Ciencia da Computacao 1o Periodo';
            $response = implode(', ', array_keys($data['respostas']));
            $assign  = $data['assign'] ?? 'usuario@demo';
            $release = $data['release'] ?? 'DEMO';
            $resume  = $data['pontuacoes']['Ansiedade'] ?? 'N/A';

            $payload = json_encode([
                'curso'     => $classe,
                'ano'       => $data['ano'] ?? '1 Periodo',
                'qid'       => $qid,
                'resposta'  => $response,
                'pontuacao' => $resume,
                'avaliacao' => $this->avaliacaoAnsiedade((int)$resume),
            ], JSON_UNESCAPED_UNICODE);

            $sql = "
                INSERT INTO perfil (id, qid, classe, response, assign, payload, release, resume)
                VALUES (
                    '$id',
                    '$qid',
                    '$classe',
                    '$response',
                    '$assign',
                    '$payload',
                    '$release',
                    '$resume'
                );
            ";

            $this->pdo->exec($sql);

            return ['success' => true, 'id' => $id];
        } catch (PDOException $e) {
            return ['error' => 'Erro ao inserir perfil: ' . $e->getMessage()];
        }
    }

    public function getPerguntasERespostas($questionarioId) {
        $result = [];

        $sqlPerguntas = "SELECT id, nome, tipo FROM pergunta where qid = $questionarioId order by pseq";
        $perguntas = $this->pdo->query($sqlPerguntas)->fetchAll(PDO::FETCH_ASSOC);

        foreach ($perguntas as $pergunta) {
            $perguntaId = (int)$pergunta['id'];
            $sqlRespostas = "SELECT id, descricao, score, img FROM qalternativa a join cad_respostas r on a.rid = r.id WHERE pergunta = $perguntaId order by rseq";
            $respostasRaw = $this->pdo->query($sqlRespostas)->fetchAll(PDO::FETCH_ASSOC);

            $respostasFormatadas = array_map(function($resposta) {
                return [
                    'id'    => (int)$resposta['id'],
                    'text'  => $resposta['descricao'],
                    'score' => isset($resposta['score']) ? (int)$resposta['score'] : 0,
                    'image' => $resposta['img'] ?? ""
                ];
            }, $respostasRaw);

            $result[] = [
                'pergunta'  => $pergunta['nome'],
                'respostas' => $respostasFormatadas
            ];
        }

        return $result;
    }

}
?>