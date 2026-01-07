<?php
require_once __DIR__ . "/../utils/Conexao.php";

class CandidatoDAO {
    private $pdo;

    public function __construct() {
        $this->pdo = Conexao::conectar();
        if (!$this->pdo) {
            throw new Exception("Não foi possível conectar ao banco de dados");
        }
    }

    private function escreverLog($mensagem) {
        $logFile = __DIR__ . '/../logs/erros.log';
        $dir = dirname($logFile);
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        $timestamp = date('Y-m-d H:i:s');
        file_put_contents($logFile, "[$timestamp] $mensagem\n", FILE_APPEND);
    }

    public function adicionar(array $dados): int|false {
        try {
            if (empty($dados['eleicao_fk']) || empty($dados['funcionario_fk'])) {
                $this->escreverLog("Erro: eleicao_fk ou funcionario_fk não fornecidos");
                return false;
            }
            
            $eleicao_fk = (int)$dados['eleicao_fk'];
            $funcionario_fk = (int)$dados['funcionario_fk'];
            
            $this->escreverLog("Tentando adicionar candidato - Eleicao: $eleicao_fk, Usuario: $funcionario_fk");
            
            $sqlVerifica = "SELECT id_usuario FROM usuario WHERE id_usuario = :id";
            $stmtVerifica = $this->pdo->prepare($sqlVerifica);
            $stmtVerifica->execute([':id' => $funcionario_fk]);
            $usuarioExiste = $stmtVerifica->fetch();
            if (!$usuarioExiste) {
                $this->escreverLog("Erro: Usuário ID $funcionario_fk não existe no banco");
                return false;
            }
            
            $sqlVerificaEleicao = "SELECT id_eleicao FROM eleicao WHERE id_eleicao = :id";
            $stmtVerificaEleicao = $this->pdo->prepare($sqlVerificaEleicao);
            $stmtVerificaEleicao->execute([':id' => $eleicao_fk]);
            $eleicaoExiste = $stmtVerificaEleicao->fetch();
            if (!$eleicaoExiste) {
                $this->escreverLog("Erro: Eleição ID $eleicao_fk não existe no banco");
                return false;
            }
            
            $sql = "INSERT INTO candidato (
                        eleicao_fk, funcionario_fk, vice_fk, 
                        numero_candidato, foto_candidato, 
                        ativo_candidato, status_candidato
                    ) VALUES (
                        :eleicao, :func, :vice, :numero, :foto, 1, 'Pendente'
                    )";
            $stmt = $this->pdo->prepare($sql);
            
            $params = [
                ':eleicao' => $eleicao_fk,
                ':func' => $funcionario_fk,
                ':vice' => isset($dados['vice_fk']) && !empty($dados['vice_fk']) ? (int)$dados['vice_fk'] : null,
                ':numero' => isset($dados['numero_candidato']) ? (int)$dados['numero_candidato'] : 0,
                ':foto' => $dados['foto_candidato'] ?? null
            ];
            
            $stmt->execute($params);
            
            $idCandidato = (int)$this->pdo->lastInsertId();
            $this->escreverLog("Candidato inserido com ID: $idCandidato");
            
            if ($idCandidato <= 0) {
                $errorInfo = $stmt->errorInfo();
                $erroMsg = "SQLSTATE: " . ($errorInfo[0] ?? 'N/A') . ", Mensagem: " . ($errorInfo[2] ?? 'N/A');
                $this->escreverLog("Erro ao inserir candidato - $erroMsg");
                return false;
            }
            
            $sqlLista = "INSERT INTO lista_candidatos (candidato_fk, eleicao_fk, status_lista_candidato) 
                         VALUES (:candidato, :eleicao, 'Pendente')";
            $stmtLista = $this->pdo->prepare($sqlLista);
            $stmtLista->execute([
                ':candidato' => $idCandidato,
                ':eleicao' => $eleicao_fk
            ]);
            
            $errorInfoLista = $stmtLista->errorInfo();
            if (isset($errorInfoLista[0]) && $errorInfoLista[0] !== '00000' && $errorInfoLista[0] !== null) {
                $erroMsg = "SQLSTATE: " . ($errorInfoLista[0] ?? 'N/A') . ", Mensagem: " . ($errorInfoLista[2] ?? 'N/A');
                $this->escreverLog("Erro ao inserir na lista_candidatos - $erroMsg");
                $sqlDelete = "DELETE FROM candidato WHERE id_candidato = :id";
                $stmtDelete = $this->pdo->prepare($sqlDelete);
                $stmtDelete->execute([':id' => $idCandidato]);
                return false;
            }
            
            $idListaCandidato = (int)$this->pdo->lastInsertId();
            if ($idListaCandidato <= 0) {
                $this->escreverLog("Aviso: lastInsertId da lista_candidatos retornou 0, mas pode estar funcionando");
            }
            
            $this->escreverLog("Candidato ID $idCandidato adicionado com sucesso!");
            return $idCandidato;
        } catch (PDOException $e) {
            $erroMsg = "Erro PDO: " . $e->getMessage() . " | Código: " . $e->getCode();
            $this->escreverLog($erroMsg);
            return false;
        } catch (Exception $e) {
            $erroMsg = "Erro geral: " . $e->getMessage();
            $this->escreverLog($erroMsg);
            return false;
        }
    }

    public function listarPorEleicao(int $eleicao_fk) {
        try {
            $sql = "SELECT c.*, u.nome_usuario, u.sobrenome_usuario, 
                           u2.nome_usuario as nome_vice, u2.sobrenome_usuario as sobrenome_vice,
                           lc.id_lista_candidato, lc.quantidade_votos_lista_candidato
                    FROM candidato c 
                    INNER JOIN usuario u ON c.funcionario_fk = u.id_usuario 
                    LEFT JOIN usuario u2 ON c.vice_fk = u2.id_usuario
                    LEFT JOIN lista_candidatos lc ON c.id_candidato = lc.candidato_fk AND lc.eleicao_fk = c.eleicao_fk
                    WHERE c.eleicao_fk = :eleicao AND c.ativo_candidato = 1
                    ORDER BY c.numero_candidato ASC, c.id_candidato ASC";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':eleicao' => (int)$eleicao_fk]);
            $resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $resultado ? $resultado : [];
        } catch (PDOException $e) {
            error_log("Erro ao listar candidatos: " . $e->getMessage());
            return [];
        }
    }

    public function confirmar(int $id_candidato): bool {
        try {
            $sql = "UPDATE candidato SET status_candidato = 'Confirmado' WHERE id_candidato = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id' => $id_candidato]);
            
            $sqlLista = "UPDATE lista_candidatos SET status_lista_candidato = 'Confirmado' 
                         WHERE candidato_fk = :id";
            $stmtLista = $this->pdo->prepare($sqlLista);
            $stmtLista->execute([':id' => $id_candidato]);
            
            return true;
        } catch (PDOException $e) {
            error_log("Erro ao confirmar candidato: " . $e->getMessage());
            return false;
        }
    }

    public function getPorNumero(int $numero, int $eleicao_fk) {
        try {
            $sql = "SELECT c.*, lc.id_lista_candidato 
                    FROM candidato c
                    JOIN lista_candidatos lc ON c.id_candidato = lc.candidato_fk
                    WHERE c.numero_candidato = :numero AND c.eleicao_fk = :eleicao";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':numero' => $numero,
                ':eleicao' => $eleicao_fk
            ]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar candidato: " . $e->getMessage());
            return false;
        }
    }

    public function numeroJaExiste(int $numero, int $eleicao_fk, int $excluirId = 0): bool {
        try {
            $sql = "SELECT id_candidato FROM candidato 
                    WHERE numero_candidato = :numero AND eleicao_fk = :eleicao";
            if ($excluirId > 0) {
                $sql .= " AND id_candidato != :excluir";
            }
            $stmt = $this->pdo->prepare($sql);
            $params = [':numero' => $numero, ':eleicao' => $eleicao_fk];
            if ($excluirId > 0) {
                $params[':excluir'] = $excluirId;
            }
            $stmt->execute($params);
            return $stmt->fetch() !== false;
        } catch (PDOException $e) {
            return false;
        }
    }
}
?>