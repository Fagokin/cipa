<?php
require_once __DIR__ . "/../utils/Conexao.php";

class VotoDAO {
    private $pdo;

    public function __construct() {
        $this->pdo = Conexao::conectar();
    }

    public function registrar(array $dados): array|false {
        try {
            $sql = "INSERT INTO voto (
                        funcionario_fk, eleicao_fk, lista_candidato_fk,
                        data_hora_voto, ip_voto, hash_confirmacao
                    ) VALUES (
                        :funcionario, :eleicao, :candidato, NOW(), :ip, :hash
                    )";

            $hash = md5(uniqid(rand(), true) . time());

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':funcionario' => $dados['funcionario_fk'],
                ':eleicao' => $dados['eleicao_fk'],
                ':candidato' => $dados['lista_candidato_fk'] ?? null,
                ':ip' => $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0',
                ':hash' => $hash
            ]);

            return [
                'id' => $this->pdo->lastInsertId(),
                'hash' => $hash
            ];
        } catch (PDOException $e) {
            error_log("Erro ao registrar voto: " . $e->getMessage());
            return false;
        }
    }

    public function jaVotou(int $idUsuario, int $idEleicao): bool {
        try {
            $sql = "SELECT id_voto FROM voto 
                    WHERE funcionario_fk = :usuario AND eleicao_fk = :eleicao";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':usuario' => $idUsuario,
                ':eleicao' => $idEleicao
            ]);
            return $stmt->fetch() !== false;
        } catch (PDOException $e) {
            error_log("Erro ao verificar voto: " . $e->getMessage());
            return false;
        }
    }

    public function getVotoPorHash(string $hash) {
        try {
            $sql = "SELECT v.*, u.nome_usuario, u.sobrenome_usuario, 
                           e.titulo_eleicao, c.numero_candidato,
                           lc.nome_usuario as nome_candidato, lc.sobrenome_usuario as sobrenome_candidato
                    FROM voto v
                    JOIN usuario u ON v.funcionario_fk = u.id_usuario
                    JOIN eleicao e ON v.eleicao_fk = e.id_eleicao
                    LEFT JOIN lista_candidatos lc ON v.lista_candidato_fk = lc.id_lista_candidato
                    LEFT JOIN candidato c ON lc.candidato_fk = c.id_candidato
                    WHERE v.hash_confirmacao = :hash";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':hash' => $hash]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar voto: " . $e->getMessage());
            return false;
        }
    }

    public function getResultadosEleicao(int $idEleicao) {
        try {
            $sql = "SELECT lc.id_lista_candidato, 
                           c.id_candidato,
                           c.numero_candidato,
                           u.nome_usuario, u.sobrenome_usuario,
                           COUNT(v.id_voto) as total_votos
                    FROM lista_candidatos lc
                    INNER JOIN candidato c ON lc.candidato_fk = c.id_candidato
                    INNER JOIN usuario u ON c.funcionario_fk = u.id_usuario
                    LEFT JOIN voto v ON v.lista_candidato_fk = lc.id_lista_candidato AND v.eleicao_fk = :eleicao
                    WHERE lc.eleicao_fk = :eleicao AND c.ativo_candidato = 1
                    GROUP BY lc.id_lista_candidato, c.id_candidato, c.numero_candidato, u.nome_usuario, u.sobrenome_usuario
                    ORDER BY total_votos DESC, c.numero_candidato ASC";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':eleicao' => $idEleicao]);
            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $sqlBranco = "SELECT quantidade_branco, quantidade_nulo 
                          FROM branco_nulo 
                          WHERE eleicao_fk = :eleicao";
            $stmtBranco = $this->pdo->prepare($sqlBranco);
            $stmtBranco->execute([':eleicao' => $idEleicao]);
            $brancoNulo = $stmtBranco->fetch(PDO::FETCH_ASSOC);

            $sqlTotal = "SELECT COUNT(*) as total FROM voto WHERE eleicao_fk = :eleicao";
            $stmtTotal = $this->pdo->prepare($sqlTotal);
            $stmtTotal->execute([':eleicao' => $idEleicao]);
            $totalResult = $stmtTotal->fetch(PDO::FETCH_ASSOC);
            $totalVotos = (int)($totalResult['total'] ?? 0);

            return [
                'candidatos' => $resultados ? $resultados : [],
                'branco_nulo' => $brancoNulo ?: ['quantidade_branco' => 0, 'quantidade_nulo' => 0],
                'total_votos' => $totalVotos
            ];
        } catch (PDOException $e) {
            error_log("Erro ao buscar resultados: " . $e->getMessage());
            return [
                'candidatos' => [],
                'branco_nulo' => ['quantidade_branco' => 0, 'quantidade_nulo' => 0],
                'total_votos' => 0
            ];
        }
    }

    public function registrarVotoBranco(int $idUsuario, int $idEleicao): bool {
        try {
            $sql = "INSERT INTO branco_nulo (eleicao_fk, quantidade_branco) 
                    VALUES (:eleicao, 1)
                    ON DUPLICATE KEY UPDATE quantidade_branco = quantidade_branco + 1";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':eleicao' => $idEleicao]);

            $sqlVoto = "INSERT INTO voto (funcionario_fk, eleicao_fk, data_hora_voto, ip_voto, hash_confirmacao) 
                        VALUES (:usuario, :eleicao, NOW(), :ip, :hash)";
            $hash = md5(uniqid(rand(), true) . time());
            $stmtVoto = $this->pdo->prepare($sqlVoto);
            $stmtVoto->execute([
                ':usuario' => $idUsuario,
                ':eleicao' => $idEleicao,
                ':ip' => $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0',
                ':hash' => $hash
            ]);

            return true;
        } catch (PDOException $e) {
            error_log("Erro ao registrar voto branco: " . $e->getMessage());
            return false;
        }
    }
}
?>

