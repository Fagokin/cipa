<?php
require_once __DIR__ . "/../utils/Conexao.php";

class EleicaoDAO {
    private $pdo;

    public function __construct() {
        $this->pdo = Conexao::conectar();
    }

    public function criar(array $dados): int|false {
        try {
            $sql = "INSERT INTO eleicao (
                        titulo_eleicao, descricao_eleicao, data_inicio_eleicao, data_fim_eleicao,
                        ativo_eleicao, status_eleicao, permite_voto_branco
                    ) VALUES (
                        :titulo, :descricao, :inicio, :fim, 1, 'Em espera', :voto_branco
                    )";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':titulo' => $dados['titulo_eleicao'],
                ':descricao' => $dados['descricao_eleicao'],
                ':inicio' => $dados['data_inicio_eleicao'],
                ':fim' => $dados['data_fim_eleicao'],
                ':voto_branco' => $dados['permite_voto_branco']
            ]);
            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function atualizar(array $dados, int $id): bool {
        try {
            $sql = "UPDATE eleicao SET
                        titulo_eleicao = :titulo,
                        descricao_eleicao = :descricao,
                        data_inicio_eleicao = :inicio,
                        data_fim_eleicao = :fim,
                        permite_voto_branco = :voto_branco
                    WHERE id_eleicao = :id";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':titulo' => $dados['titulo_eleicao'],
                ':descricao' => $dados['descricao_eleicao'],
                ':inicio' => $dados['data_inicio_eleicao'],
                ':fim' => $dados['data_fim_eleicao'],
                ':voto_branco' => $dados['permite_voto_branco'],
                ':id' => $id
            ]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function listarTodas() {
        $sql = "SELECT * FROM eleicao ORDER BY data_inicio_eleicao DESC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPorId(int $id) {
        $sql = "SELECT * FROM eleicao WHERE id_eleicao = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>