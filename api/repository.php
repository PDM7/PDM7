<?php
namespace Database;

use PDO;
use PDOException;
use Exception;
use IntlDateFormatter;

class Database
{
    private $host;
    private $dbName;
    private $username;
    private $password;
    private $port;

    public $con;

    public function __construct()
    {
        $this->host     = getenv('DB_HOST');
        $this->dbName   = getenv('DB_NAME');
        $this->username = getenv('DB_USER');
        $this->password = getenv('DB_PASS');
        $this->port     = getenv('DB_PORT');
    }

    public function connect()
    {
        $this->con = null;

        try {
            $this->con = new PDO(
                "pgsql:host={$this->host};port={$this->port};dbname={$this->dbName}",
                $this->username,
                $this->password
            );
            $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Erro de conexão: " . $e->getMessage();
        }

        return $this->con;
    }



    public function fetch($query, $params = [])
    {
        try {
            $stmt = $this->con->prepare($query);
         
    
            if (!empty($params)) {
                foreach ($params as $key => $value) {
                  
                    // Trate booleanos como inteiros
                    if (is_bool($value)) {
                     
                        $stmt->bindValue($key, $value ? 1 : 0, PDO::PARAM_INT);
                    } elseif (is_int($value)) {
                      
                        $stmt->bindValue($key, $value, PDO::PARAM_INT);  // Para inteiros
                    } else {
                        
                        $stmt->bindValue($key, $value, PDO::PARAM_STR);  // Para strings (default)
                    }
                }
            }
    
            $stmt->execute();
            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
            return !empty($resultados) ? $resultados : false;
        } catch (PDOException $e) {
            echo "Erro: " . $e->getMessage();
            return false;
        }
    }
    
    public function inserir($table_name, $values) {
        // Verifica se já existe uma transação ativa
        $transacaoExistente = $this->con->inTransaction();
    
        if (!$transacaoExistente) {
            $this->con->beginTransaction();
        }
        
        try {
            $attributes_string = '"' . implode('","', array_keys($values)) . '"';

            $placeholders = implode(',', array_fill(0, count($values), '?'));
        
            $sql = "INSERT INTO $table_name ($attributes_string) VALUES ($placeholders)";
            $stmt = $this->con->prepare($sql);
            $stmt->execute(array_values($values));
        
            // Apenas commit se a transação foi iniciada aqui
            if (!$transacaoExistente) {
                $this->con->commit();
            }
    
            return $this->con->lastInsertId(); 
        } catch (PDOException $e) {
            // Apenas rollback se a transação foi iniciada aqui
            if (!$transacaoExistente) {
                $this->con->rollBack();
            }
            echo "Erro: " . $e->getMessage();
            return false;
        }
    }
    
    
    
    
    public function alterar($tabela, $valores, $onde) {
        // Verifica se já existe uma transação ativa
        $transacaoExistente = $this->con->inTransaction();
    
        if (!$transacaoExistente) {
            $this->con->beginTransaction();
        }
    
        try {
            foreach ($valores as $atributo => $valor) {
                $set[] = "\"$atributo\" = ?";
                $parametros[] = $valor;
            }
            $set = implode(", ", $set);
    
            foreach ($onde as $referencia => $pesquisa) {
                if (is_array($pesquisa)) {
                    // Suporte para operadores personalizados, como 'NOT IN'
                    $operador = isset($pesquisa['operador']) ? $pesquisa['operador'] : '=';
                    $valor = $pesquisa['valor'];
                    if ($operador === 'IN' || $operador === 'NOT IN') {
                        $placeholders = implode(", ", array_fill(0, count($valor), "?"));
                        $where[] = "$referencia $operador ($placeholders)";
                        $parametros = array_merge($parametros, $valor);
                    } else {
                        $where[] = "$referencia $operador ?";
                        $parametros[] = $valor;
                    }
                } else {
                    $where[] = "$referencia = ?";
                    $parametros[] = $pesquisa;
                }
            }
            $where = implode(" AND ", $where);
    
            $sql = "UPDATE $tabela SET $set WHERE $where";
            $stmt = $this->con->prepare($sql);
    
            if ($stmt->execute($parametros)) {
                // Apenas commit se a transação foi iniciada aqui
                if (!$transacaoExistente) {
                    $this->con->commit();
                }
                return true;
            } else {
                throw new Exception("Erro ao executar a atualização: " . implode(", ", $stmt->errorInfo()));
            }
        } catch (Exception $e) {
            // Apenas rollback se a transação foi iniciada aqui
            if (!$transacaoExistente) {
                $this->con->rollBack();
            }
            echo "Erro: " . $e->getMessage();
            return false;
        }
    }
    
        public function excluir($tabela, $onde) {
        
        $transacaoExistente = $this->con->inTransaction();
    
        if (!$transacaoExistente) {
            $this->con->beginTransaction();
        }
        
        
        try {
            foreach ($onde as $referencia => $pesquisa) {
                $where[] = "$referencia = ?";
                $parametros[] = $pesquisa;
            }
            $where = implode(" AND ", $where);
            $sql = "DELETE FROM $tabela WHERE $where";
            $stmt = $this->con->prepare($sql);
            
            if ($stmt->execute($parametros)) {
                if (!$transacaoExistente) {
                    $this->con->commit();
                }
                return true;
            } else {
                throw new Exception("Erro ao executar a exclusão: " . implode(", ", $stmt->errorInfo()));
            }
        } catch (Exception $e) {
            if (!$transacaoExistente) {
                $this->con->rollBack();
            }
            echo "Erro: " . $e->getMessage();
            return false;
        }
    }
    
    
     public function dataHoraServidor() {
        try {
            $stmt = $this->con->query("SELECT NOW()");
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['now']; // formato: 2025-05-13 10:35:42
        } catch (PDOException $e) {
            return 'Erro ao obter data/hora: ' . $e->getMessage();
        }
    }

    public function inserirAnalise($data) {
        //prepared statements
    if (!$data || !isset($data['respostas']) || !isset($data['pontuacoes'])) {
        return ['error' => 'Dados inválidos para inserção'];
    }

    try {
        $qid = $data['qid'] ?? 1;
        $classe = $data['classe'] ?? 'Ciências da Computação - 1º Período';
        $response = implode(', ', array_map(fn($r) => $r['score'], $data['respostas']));

        $assign = $data['assign'] ?? time();
        $release = $data['salvar'] ?? 'Master';
        $resume = $data['pontuacoes']['Ansiedade'] ?? 'N/A';

        $name = $data['name'] ?? "Sem nome";
        $age = $data['age'] ?? '';
        $period = $data['period'] ?? '';
        $institution = $data['institution'] ?? '';
        $gender = $data['gender'] ?? '';
        $graduation = $data['graduation'] ?? '';
        $cep = $data['cep'] ?? "-";
        $state = $data['state'] ?? "-";
        $city = $data['city'] ?? "-";

        $payload = json_encode($data);

        $sql = "INSERT INTO public.analise 
            (qid, classe, response, assign, payload, release, resume, pseudonimo, idade, periodo, instituicao, genero, curso, cep, estado, localidade)
            VALUES 
            (:qid, :classe, :response, :assign, :payload, :release, :resume, :name, :age, :period, :institution, :gender, :graduation, :cep, :state, :city)";

        $stmt = $this->con->prepare($sql);
        $stmt->execute([
            ':qid' => $qid,
            ':classe' => $classe,
            ':response' => $response,
            ':assign' => $assign,
            ':payload' => $payload,
            ':release' => $release,
            ':resume' => $resume,
            ':name' => $name,
            ':age' => $age,
            ':period' => $period,
            ':institution' => $institution,
            ':gender' => $gender,
            ':graduation' => $graduation,
            ':cep' => $cep,
            ':state' => $state,
            ':city' => $city,
        ]);

        $id = $this->con->lastInsertId();

        return ['success' => true, 'id' => $id];
    } catch (PDOException $e) {
        return ['error' => 'Erro ao inserir: ' . $e->getMessage()];
    }
}


    public function getPerguntasERespostas($questionarioId) {
        $result = [];

        $sqlPerguntas = "SELECT id, nome, tipo FROM pergunta where qid = $questionarioId order by pseq";
        $perguntas = $this->con->query($sqlPerguntas)->fetchAll(PDO::FETCH_ASSOC);

        foreach ($perguntas as $pergunta) {
            $perguntaId = (int)$pergunta['id'];
            $sqlRespostas = "SELECT id, descricao, score, img FROM qalternativa a join cad_respostas r on a.rid = r.id WHERE pergunta = $perguntaId order by rseq";
            $respostasRaw = $this->con->query($sqlRespostas)->fetchAll(PDO::FETCH_ASSOC);

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

$db = new Database();
$con = $db->connect();
$formatter = new IntlDateFormatter(
    'pt_BR',
    IntlDateFormatter::FULL,
    IntlDateFormatter::NONE,
    'America/Sao_Paulo',
    IntlDateFormatter::GREGORIAN,
    "d 'de' MMMM 'de' y"
);
date_default_timezone_set('America/Sao_Paulo');

if (isset($_REQUEST['salvar'])) {
    $raw = file_get_contents('php://input');
    $data = json_decode($raw, true);

    if ($data === null) {
        echo json_encode(['status' => 'erro', 'mensagem' => 'JSON inválido', 'raw' => $raw]);
        exit;
    }

    $resultado = $db->inserirAnalise($data);

    if (isset($resultado['success'])) {
        echo json_encode(['status' => 'ok', 'id' => $resultado['id']]);
    } else {
        echo json_encode(['status' => 'erro', 'mensagem' => $resultado['error']]);
    }
}
