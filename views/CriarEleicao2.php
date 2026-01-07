<?php
require_once __DIR__ . "/../utils/Sessao.php";
Sessao::requerAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['eleicao_dados'] = array_merge($_SESSION['eleicao_dados'], $_POST);
    header("Location: CriarEleicao3.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>2º passo: Datas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h3>Eleição de CIPA - Passo 2: Datas</h3>
        </div>
        <div class="card-body">
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Data Fim da Última Eleição</label>
                    <input type="date" name="data_fim_ultima" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Data de Início da Eleição</label>
                    <input type="date" name="data_inicio_eleicao" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Data de Fim da Eleição</label>
                    <input type="date" name="data_fim_eleicao" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Data de Abertura das Candidaturas</label>
                    <input type="date" name="data_abertura_candidaturas" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Data de Comunicação com o Sindicato</label>
                    <input type="date" name="data_comunicacao_sindicato" class="form-control" required>
                </div>

                <div class="mt-4">
                    <a href="CriarEleicao1.php" class="btn btn-secondary">Voltar</a>
                    <button type="submit" class="btn btn-success float-end">Avançar</button>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>

