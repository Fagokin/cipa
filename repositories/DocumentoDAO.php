<?php
require_once __DIR__ . "/../utils/Conexao.php";

class DocumentoDAO {
    private $pdo;

    public function __construct() {
        $this->pdo = Conexao::conectar();
    }

    public function criar(array $dados): int|false {
        try {
            $sql = "INSERT INTO documentos (
                        titulo_documento, tipo_documento, arquivo_documento,
                        eleicao_fk, data_inicio_documento, data_fim_documento
                    ) VALUES (
                        :titulo, :tipo, :arquivo, :eleicao, :inicio, :fim
                    )";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':titulo' => $dados['titulo_documento'],
                ':tipo' => $dados['tipo_documento'],
                ':arquivo' => $dados['arquivo_documento'],
                ':eleicao' => $dados['eleicao_fk'] ?? null,
                ':inicio' => $dados['data_inicio_documento'] ?? null,
                ':fim' => $dados['data_fim_documento'] ?? null
            ]);

            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            error_log("Erro ao criar documento: " . $e->getMessage());
            return false;
        }
    }

    public function listarTodos(int $eleicaoId = null) {
        try {
            if ($eleicaoId) {
                $sql = "SELECT d.*, e.titulo_eleicao 
                        FROM documentos d 
                        LEFT JOIN eleicao e ON d.eleicao_fk = e.id_eleicao 
                        WHERE d.eleicao_fk = :eleicao 
                        ORDER BY d.data_registro_documento DESC";
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute([':eleicao' => $eleicaoId]);
            } else {
                $sql = "SELECT d.*, e.titulo_eleicao 
                        FROM documentos d 
                        LEFT JOIN eleicao e ON d.eleicao_fk = e.id_eleicao 
                        ORDER BY d.data_registro_documento DESC";
                $stmt = $this->pdo->query($sql);
            }
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao listar documentos: " . $e->getMessage());
            return [];
        }
    }

    public function getPorId(int $id) {
        try {
            $sql = "SELECT d.*, e.titulo_eleicao 
                    FROM documentos d 
                    LEFT JOIN eleicao e ON d.eleicao_fk = e.id_eleicao 
                    WHERE d.id_documento = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar documento: " . $e->getMessage());
            return false;
        }
    }

    public function excluir(int $id): bool {
        try {
            $sql = "DELETE FROM documentos WHERE id_documento = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id' => $id]);
            return true;
        } catch (PDOException $e) {
            error_log("Erro ao excluir documento: " . $e->getMessage());
            return false;
        }
    }

    public function atualizar(array $dados, int $id): bool {
        try {
            $sql = "UPDATE documentos SET
                        titulo_documento = :titulo,
                        tipo_documento = :tipo,
                        data_inicio_documento = :inicio,
                        data_fim_documento = :fim
                        " . (!empty($dados['arquivo_documento']) ? ", arquivo_documento = :arquivo" : "") . "
                    WHERE id_documento = :id";

            $stmt = $this->pdo->prepare($sql);
            
            $params = [
                ':titulo' => $dados['titulo_documento'],
                ':tipo' => $dados['tipo_documento'],
                ':inicio' => $dados['data_inicio_documento'] ?? null,
                ':fim' => $dados['data_fim_documento'] ?? null,
                ':id' => $id
            ];

            if (!empty($dados['arquivo_documento'])) {
                $params[':arquivo'] = $dados['arquivo_documento'];
            }

            $stmt->execute($params);
            return true;
        } catch (PDOException $e) {
            error_log("Erro ao atualizar documento: " . $e->getMessage());
            return false;
        }
    }
}
?>

