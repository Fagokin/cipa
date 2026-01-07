<?php
require_once __DIR__ . "/../utils/Sessao.php";
Sessao::requerAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    session_start();
    $_SESSION['eleicao_dados'] = $_POST;
    header("Location: CriarEleicao2.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>1º passo: Programação</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h3>Eleição de CIPA - Passo 1: Programação</h3>
        </div>
        <div class="card-body">
            <form method="POST">
                <div class="mb-3">
                    <label class="form-label">Nome da Eleição</label>
                    <input type="text" name="titulo_eleicao" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Quantidade de trabalhadores</label>
                    <input type="number" name="quantidade_trabalhadores" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Grau de risco</label>
                    <div>
                        <label><input type="radio" name="grau_risco" value="1" required> 1</label>
                        <label class="ms-3"><input type="radio" name="grau_risco" value="2"> 2</label>
                        <label class="ms-3"><input type="radio" name="grau_risco" value="3"> 3</label>
                        <label class="ms-3"><input type="radio" name="grau_risco" value="4"> 4</label>
                    </div>
                </div>

                <div class="row">
                    <div class="col">
                        <label class="form-label">Quantidade de efetivos</label>
                        <input type="number" name="quantidade_efetivos" class="form-control" required>
                    </div>
                    <div class="col">
                        <label class="form-label">Quantidade de suplentes</label>
                        <input type="number" name="quantidade_suplentes" class="form-control" required>
                    </div>
                </div>

                <div class="mt-4">
                    <a href="Eleicoes.php" class="btn btn-secondary">Voltar</a>
                    <button type="submit" class="btn btn-success float-end">Avançar</button>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>

