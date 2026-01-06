<?php
require_once __DIR__ . "/../utils/Conexao.php";

class CandidatoDAO {
    private $pdo;

    public function __construct() {
        $this->pdo = Conexao::conectar();
    }

    public function adicionar(int $eleicao_fk, int $funcionario_fk): bool {
        try {
            $sql = "INSERT INTO candidato (eleicao_fk, funcionario_fk, ativo_candidato, status_candidato) VALUES (:eleicao, :func, 1, 'Pendente')";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':eleicao' => $eleicao_fk, ':func' => $funcionario_fk]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function listarPorEleicao(int $eleicao_fk) {
        $sql = "SELECT c.*, u.nome_usuario, u.sobrenome_usuario FROM candidato c JOIN usuario u ON c.funcionario_fk = u.id_usuario WHERE c.eleicao_fk = :eleicao";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':eleicao' => $eleicao_fk]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function confirmar(int $id_candidato): bool {
        $sql = "UPDATE candidato SET status_candidato = 'Confirmado' WHERE id_candidato = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id_candidato]);
        return true;
    }
}
?>