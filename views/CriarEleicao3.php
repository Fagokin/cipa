<?php
require_once __DIR__ . "/../utils/Sessao.php";
Sessao::requerAdmin();
Sessao::iniciar();

require_once __DIR__ . "/../repositories/UsuarioDAO.php";

$usuarioDAO = new UsuarioDAO();
$usuarios = $usuarioDAO->listarTodos();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selecionados = $_POST['candidatos'] ?? [];
    if (empty($selecionados)) {
        $erro = "Por favor, selecione pelo menos um candidato.";
    } else {
        if (!isset($_SESSION['eleicao_dados'])) {
            $_SESSION['eleicao_dados'] = [];
        }
        $_SESSION['eleicao_dados']['candidatos'] = $selecionados;
        header("Location: CriarEleicao4.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>3º passo: Candidatos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h3>Eleição de CIPA - Passo 3: Candidatos</h3>
        </div>
        <div class="card-body">
            <?php if (isset($erro)): ?>
                <div class="alert alert-danger">
                    <?= htmlspecialchars($erro) ?>
                </div>
            <?php endif; ?>
            
            <div class="mb-4">
                <strong>Data para início da candidatura:</strong> 
                <?= $_SESSION['eleicao_dados']['data_abertura_candidaturas'] ?? 'Não definida' ?>
            </div>

            <div class="mb-4">
                <strong>Quantidade mínima de candidatos:</strong> 2
            </div>

            <form method="POST">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5>Candidatos</h5>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="selecionarTodos"></th>
                                <th>Matrícula</th>
                                <th>Nome</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($usuarios as $u): ?>
                                <tr class="<?= !$u['ativo_usuario'] ? 'table-secondary' : '' ?>">
                                    <td>
                                        <?php if ($u['ativo_usuario']): ?>
                                            <input type="checkbox" name="candidatos[]" value="<?= $u['id_usuario'] ?>">
                                        <?php endif; ?>
                                    </td>
                                    <td><?= htmlspecialchars($u['matricula_usuario']) ?></td>
                                    <td><?= htmlspecialchars($u['nome_usuario'] . ' ' . $u['sobrenome_usuario']) ?></td>
                                    <td>
                                        <?php if (!$u['ativo_usuario']): ?>
                                            <span class="text-muted">Inativo</span>
                                        <?php else: ?>
                                            <span class="text-success">Ativo</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="mt-4 d-flex justify-content-between">
                    <a href="CriarEleicao2.php" class="btn btn-secondary">Voltar</a>
                    <button type="submit" class="btn btn-success">Avançar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('selecionarTodos').addEventListener('change', function() {
    document.querySelectorAll('input[name="candidatos[]"]').forEach(cb => cb.checked = this.checked);
});
</script>
</body>
</html>

