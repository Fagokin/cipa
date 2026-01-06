<?php
require_once __DIR__ . "/../repositories/EleicaoDAO.php";

$eleicaoDAO = new EleicaoDAO();
$eleicoes = $eleicaoDAO->listarTodas(); // método que vamos criar agora
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eleições CIPA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2"><i class="fas fa-vote-yea text-primary"></i> Eleições CIPA</h1>
        <a href="CriarEleicao.php" class="btn btn-success btn-lg">
            <i class="fas fa-plus"></i> Nova Eleição
        </a>
    </div>

    <?php if (empty($eleicoes)): ?>
        <div class="alert alert-info text-center py-5">
            <i class="fas fa-info-circle fa-3x mb-3"></i>
            <h4>Nenhuma eleição cadastrada ainda</h4>
            <p>Clique em "Nova Eleição" para começar o processo eleitoral da CIPA.</p>
        </div>
    <?php else: ?>
        <div class="card shadow">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-dark">
                            <tr>
                                <th>Título da Eleição</th>
                                <th>Início</th>
                                <th>Fim</th>
                                <th>Status</th>
                                <th class="text-center">Candidatos</th>
                                <th class="text-center">Votos</th>
                                <th class="text-center">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($eleicoes as $e): ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($e['titulo_eleicao']) ?></strong></td>
                                <td><?= date('d/m/Y', strtotime($e['data_inicio_eleicao'])) ?></td>
                                <td><?= date('d/m/Y', strtotime($e['data_fim_eleicao'])) ?></td>
                                <td>
                                    <?php
                                    $status = $e['status_eleicao'];
                                    $badge = match($status) {
                                        'Em espera' => 'bg-secondary',
                                        'Em andamento' => 'bg-primary',
                                        'Finalizado' => 'bg-success',
                                        'Prorrogado' => 'bg-warning',
                                        default => 'bg-dark'
                                    };
                                    ?>
                                    <span class="badge <?= $badge ?>"><?= $status ?></span>
                                </td>
                                <td class="text-center">
                                    <a href="Candidatos.php?id_eleicao=<?= $e['id_eleicao'] ?>" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-users"></i> Gerenciar
                                    </a>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-info">0 votos</span> <!-- depois vamos contar -->
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="LinkVotacao.php?id=<?= $e['id_eleicao'] ?>" class="btn btn-info btn-sm" title="Gerar link de votação">
                                            <i class="fas fa-link"></i>
                                        </a>
                                        <a href="Resultados.php?id=<?= $e['id_eleicao'] ?>" class="btn btn-secondary btn-sm" title="Ver resultados">
                                            <i class="fas fa-chart-bar"></i>
                                        </a>
                                        <button class="btn btn-danger btn-sm" title="Excluir eleição" onclick="confirmarExclusao(<?= $e['id_eleicao'] ?>)">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmarExclusao(id) {
    Swal.fire({
        title: 'Tem certeza?',
        text: "Esta eleição será excluída permanentemente!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sim, excluir',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = 'ExcluirEleicao.php?id=' + id;
        }
    });
}
</script>

</body>
</html>