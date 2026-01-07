<?php
require_once __DIR__ . "/../utils/Sessao.php";
Sessao::requerAdmin();

require_once __DIR__ . "/../repositories/UsuarioDAO.php";

$usuarioDAO = new UsuarioDAO();

// Pega candidatos da sessão
$candidatos_ids = $_SESSION['eleicao_dados']['candidatos'] ?? [];
$candidatos = [];
foreach ($candidatos_ids as $id) {
    $u = $usuarioDAO->getUsuarioPorId($id);
    if ($u) $candidatos[] = $u;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['confirmar_divulgacao']) && isset($_POST['confirmar_leitura'])) {
        $_SESSION['eleicao_dados']['divulgado'] = true;
        header("Location: CriarEleicao5.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>4º passo: Confirmação</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h3>Eleição de CIPA - Passo 4: Confirmação</h3>
        </div>
        <div class="card-body">
            <p><strong>Data para divulgação dos candidatos:</strong> 
               <?= $_SESSION['eleicao_dados']['data_abertura_candidaturas'] ?? 'Não definida' ?></p>
            <p><strong>Quantidade mínima de candidatos:</strong> 2</p>
            <p><strong>Quantidade de candidatos selecionados:</strong> <?= count($candidatos) ?></p>

            <h5 class="mt-4">Candidatos:</h5>
            <div class="list-group mb-4">
                <?php foreach ($candidatos as $c): ?>
                    <div class="list-group-item">
                        <?= htmlspecialchars($c['nome_usuario'] . ' ' . $c['sobrenome_usuario']) ?>
                    </div>
                <?php endforeach; ?>
            </div>

            <form method="POST">
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="confirmar_divulgacao" id="divulgar" required>
                    <label class="form-check-label" for="divulgar">
                        Divulgar a lista de candidatos
                    </label>
                </div>

                <div class="alert alert-warning">
                    <strong>ATENÇÃO:</strong> Ao confirmar, as informações não poderão mais ser alteradas.
                </div>

                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" name="confirmar_leitura" id="leitura" required>
                    <label class="form-check-label" for="leitura">
                        Confirmo que li e entendi o aviso acima
                    </label>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="CriarEleicao3.php" class="btn btn-secondary">Voltar</a>
                    <button type="submit" class="btn btn-success">Confirmar e Finalizar</button>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>

