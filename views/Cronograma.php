<?php
require_once __DIR__ . "/../repositories/EleicaoDAO.php";

$eleicaoDAO = new EleicaoDAO();

$idEleicao = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$eleicao = null;

if ($idEleicao > 0) {
    $eleicao = $eleicaoDAO->getPorId($idEleicao);
} else {
    $eleicoes = $eleicaoDAO->listarTodas();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cronograma - Eleição CIPA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
    </style>
</head>
<body class="bg-light">

<div class="container mt-5">
    <?php if ($eleicao): ?>
        <div class="card shadow mb-4">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-0"><i class="fas fa-calendar-alt"></i> Cronograma - <?= htmlspecialchars($eleicao['titulo_eleicao']) ?></h3>
            </div>
            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-md-6">
                        <p><strong><i class="fas fa-calendar-check"></i> Início:</strong> 
                           <?= date('d/m/Y', strtotime($eleicao['data_inicio_eleicao'])) ?></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong><i class="fas fa-calendar-times"></i> Fim:</strong> 
                           <?= date('d/m/Y', strtotime($eleicao['data_fim_eleicao'])) ?></p>
                    </div>
                </div>

                <p class="text-muted"><?= htmlspecialchars($eleicao['descricao_eleicao'] ?? '') ?></p>
            </div>
        </div>

        <div class="text-center mt-4 mb-4">
            <a href="Votar.php?id=<?= $idEleicao ?>" class="btn btn-success btn-lg me-2">
                <i class="fas fa-vote-yea"></i> Votar
            </a>
            <a href="Candidatar.php?id=<?= $idEleicao ?>" class="btn btn-info btn-lg">
                <i class="fas fa-user-tie"></i> Candidatar-se
            </a>
        </div>

    <?php else: ?>
        <div class="card shadow">
            <div class="card-header">
                <h3 class="mb-0"><i class="fas fa-calendar-alt"></i> Cronogramas das Eleições</h3>
            </div>
            <div class="card-body">
                <?php if (empty($eleicoes)): ?>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> Nenhuma eleição cadastrada.
                    </div>
                <?php else: ?>
                    <div class="list-group">
                        <?php foreach ($eleicoes as $e): ?>
                            <a href="Cronograma.php?id=<?= $e['id_eleicao'] ?>" class="list-group-item list-group-item-action">
                                <h5><?= htmlspecialchars($e['titulo_eleicao']) ?></h5>
                                <p class="mb-1">
                                    <i class="fas fa-calendar"></i> 
                                    <?= date('d/m/Y', strtotime($e['data_inicio_eleicao'])) ?> - 
                                    <?= date('d/m/Y', strtotime($e['data_fim_eleicao'])) ?>
                                </p>
                                <small class="text-muted"><?= htmlspecialchars($e['descricao_eleicao'] ?? '') ?></small>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

