<?php
require_once __DIR__ . "/../utils/Sessao.php";
require_once __DIR__ . "/../repositories/EleicaoDAO.php";
require_once __DIR__ . "/../repositories/CandidatoDAO.php";
require_once __DIR__ . "/../repositories/VotoDAO.php";

Sessao::requerAdmin();

$eleicaoDAO = new EleicaoDAO();
$candidatoDAO = new CandidatoDAO();
$votoDAO = new VotoDAO();

$mensagem = "";
$eleicao = null;
$candidatos = [];
$resultados = [];

if (isset($_GET['id'])) {
    $idEleicao = (int)$_GET['id'];
    $eleicao = $eleicaoDAO->getPorId($idEleicao);
    
    if ($eleicao) {
        $candidatos = $candidatoDAO->listarPorEleicao($idEleicao);
        $resultados = $votoDAO->getResultadosEleicao($idEleicao);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['excluir_candidato'])) {
    $idCandidato = (int)$_POST['id_candidato'];
    $idEleicao = (int)$_POST['id_eleicao'];
    
    try {
        require_once __DIR__ . "/../utils/Conexao.php";
        $pdo = \Conexao::conectar();
        
        $sql = "DELETE FROM lista_candidatos WHERE candidato_fk = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $idCandidato]);
        
        $sql = "DELETE FROM candidato WHERE id_candidato = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $idCandidato]);
        
        $mensagem = '<div class="alert alert-success alert-dismissible fade show">
                        <strong>Sucesso!</strong> Candidato excluído com sucesso!
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                     </div>';
        
        $candidatos = $candidatoDAO->listarPorEleicao($idEleicao);
        $resultados = $votoDAO->getResultadosEleicao($idEleicao);
    } catch (PDOException $e) {
        error_log("Erro ao excluir candidato: " . $e->getMessage());
        $mensagem = '<div class="alert alert-danger alert-dismissible fade show">
                        <strong>Erro!</strong> Não foi possível excluir o candidato.
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                     </div>';
    }
}

if (!$eleicao) {
    header("Location: Eleicoes.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciar Eleição - <?= htmlspecialchars($eleicao['titulo_eleicao']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2"><i class="fas fa-cog text-primary"></i> Gerenciar Eleição</h1>
        <a href="Eleicoes.php" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Voltar
        </a>
    </div>

    <?= $mensagem ?>

    <div class="card shadow mb-4">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0"><?= htmlspecialchars($eleicao['titulo_eleicao']) ?></h4>
        </div>
        <div class="card-body">
            <p><strong>Descrição:</strong> <?= htmlspecialchars($eleicao['descricao_eleicao'] ?? 'Sem descrição') ?></p>
            <p><strong>Início:</strong> <?= date('d/m/Y H:i', strtotime($eleicao['data_inicio_eleicao'])) ?></p>
            <p><strong>Fim:</strong> <?= date('d/m/Y H:i', strtotime($eleicao['data_fim_eleicao'])) ?></p>
            <p><strong>Status:</strong> <span class="badge bg-info"><?= htmlspecialchars($eleicao['status_eleicao']) ?></span></p>
            <p><strong>Total de Votos:</strong> <span class="badge bg-success"><?= $resultados['total_votos'] ?? 0 ?></span></p>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-header bg-secondary text-white">
            <h5 class="mb-0"><i class="fas fa-users"></i> Candidatos e Votos</h5>
        </div>
        <div class="card-body">
            <?php if (empty($candidatos)): ?>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i> Nenhum candidato cadastrado nesta eleição.
                    <br><small>ID da Eleição: <?= $eleicao['id_eleicao'] ?? 'N/A' ?></small>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>Número</th>
                                <th>Candidato</th>
                                <th>Vice (se houver)</th>
                                <th class="text-center">Votos</th>
                                <th class="text-center">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($candidatos as $candidato): ?>
                                <?php
                                $votosCandidato = 0;
                                if (!empty($candidato['id_lista_candidato'])) {
                                    require_once __DIR__ . "/../utils/Conexao.php";
                                    $pdo = \Conexao::conectar();
                                    $sqlVotos = "SELECT COUNT(*) as total FROM voto 
                                                 WHERE lista_candidato_fk = :id_lista AND eleicao_fk = :eleicao";
                                    $stmtVotos = $pdo->prepare($sqlVotos);
                                    $stmtVotos->execute([
                                        ':id_lista' => $candidato['id_lista_candidato'],
                                        ':eleicao' => $eleicao['id_eleicao']
                                    ]);
                                    $resultadoVotos = $stmtVotos->fetch(PDO::FETCH_ASSOC);
                                    $votosCandidato = (int)($resultadoVotos['total'] ?? 0);
                                }
                                ?>
                                <tr>
                                    <td><strong><?= !empty($candidato['numero_candidato']) ? htmlspecialchars($candidato['numero_candidato']) : '-' ?></strong></td>
                                    <td>
                                        <strong><?= htmlspecialchars($candidato['nome_usuario'] . ' ' . $candidato['sobrenome_usuario']) ?></strong>
                                    </td>
                                    <td>
                                        <?php if (!empty($candidato['nome_vice'])): ?>
                                            <?= htmlspecialchars($candidato['nome_vice'] . ' ' . $candidato['sobrenome_vice']) ?>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-primary"><?= $votosCandidato ?> votos</span>
                                    </td>
                                    <td class="text-center">
                                        <form method="POST" style="display:inline;" 
                                              onsubmit="return confirm('Tem certeza que deseja excluir este candidato? Esta ação não pode ser desfeita!')">
                                            <input type="hidden" name="id_candidato" value="<?= $candidato['id_candidato'] ?>">
                                            <input type="hidden" name="id_eleicao" value="<?= $eleicao['id_eleicao'] ?>">
                                            <button type="submit" name="excluir_candidato" class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash"></i> Excluir
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <?php if ($resultados && isset($resultados['branco_nulo'])): ?>
                    <div class="mt-4">
                        <h6>Votos Brancos/Nulos</h6>
                        <p>
                            <span class="badge bg-secondary">Brancos: <?= $resultados['branco_nulo']['quantidade_branco'] ?? 0 ?></span>
                            <span class="badge bg-dark ms-2">Nulos: <?= $resultados['branco_nulo']['quantidade_nulo'] ?? 0 ?></span>
                        </p>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
