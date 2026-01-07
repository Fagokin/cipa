<?php
require_once __DIR__ . "/../utils/Conexao.php";

class CandidatoDAO {
    private $pdo;

    public function __construct() {
        $this->pdo = Conexao::conectar();
    }

    public function adicionar(array $dados): int|false {
        try {
            $sql = "INSERT INTO candidato (
                        eleicao_fk, funcionario_fk, vice_fk, 
                        numero_candidato, foto_candidato, 
                        ativo_candidato, status_candidato
                    ) VALUES (
                        :eleicao, :func, :vice, :numero, :foto, 1, 'Pendente'
                    )";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':eleicao' => $dados['eleicao_fk'],
                ':func' => $dados['funcionario_fk'],
                ':vice' => $dados['vice_fk'] ?? null,
                ':numero' => $dados['numero_candidato'],
                ':foto' => $dados['foto_candidato'] ?? null
            ]);
            
            $idCandidato = $this->pdo->lastInsertId();
            
            // Adiciona na lista_candidatos
            $sqlLista = "INSERT INTO lista_candidatos (candidato_fk, eleicao_fk, status_lista_candidato) 
                         VALUES (:candidato, :eleicao, 'Pendente')";
            $stmtLista = $this->pdo->prepare($sqlLista);
            $stmtLista->execute([
                ':candidato' => $idCandidato,
                ':eleicao' => $dados['eleicao_fk']
            ]);
            
            return $idCandidato;
        } catch (PDOException $e) {
            error_log("Erro ao adicionar candidato: " . $e->getMessage());
            return false;
        }
    }

    public function listarPorEleicao(int $eleicao_fk) {
        try {
            $sql = "SELECT c.*, u.nome_usuario, u.sobrenome_usuario, 
                           u2.nome_usuario as nome_vice, u2.sobrenome_usuario as sobrenome_vice,
                           lc.id_lista_candidato, lc.quantidade_votos_lista_candidato
                    FROM candidato c 
                    JOIN usuario u ON c.funcionario_fk = u.id_usuario 
                    LEFT JOIN usuario u2 ON c.vice_fk = u2.id_usuario
                    LEFT JOIN lista_candidatos lc ON c.id_candidato = lc.candidato_fk AND lc.eleicao_fk = :eleicao
                    WHERE c.eleicao_fk = :eleicao
                    ORDER BY c.numero_candidato";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':eleicao' => $eleicao_fk]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
            
            // Atualiza também na lista
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