<?php

require_once __DIR__ . "/../repositories/EleicaoDAO.php";

$eleicaoDAO = new EleicaoDAO();
$mensagem = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $dados = [
        'titulo_eleicao' => $_POST['titulo_eleicao'] ?? '',
        'data_inicio_eleicao' => $_POST['data_inicio_eleicao'] ?? '',
        'data_fim_eleicao' => $_POST['data_fim_eleicao'] ?? '',
        'descricao_eleicao' => $_POST['descricao_eleicao'] ?? '',
        'permite_voto_branco' => isset($_POST['permite_voto_branco']) ? 1 : 0
    ];

    if ($eleicaoDAO->criar($dados)) {
        $mensagem = '<div class="alert alert-success alert-dismissible fade show">
                        <strong>Sucesso!</strong> Eleição criada com sucesso!
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                     </div>';
    } else {
        $mensagem = '<div class="alert alert-danger alert-dismissible fade show">
                        <strong>Erro!</strong> Não foi possível criar a eleição.
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                     </div>';
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Eleição CIPA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h3 class="mb-0"><i class="fas fa-vote-yea"></i> Eleição de CIPA DAX-CODE</h3>
        </div>
        <div class="card-body">
            </p>

            <?= $mensagem ?>

            <form method="POST">
                <div class="mb-4">
                    <label for="titulo_eleicao" class="form-label fw-bold">Nome</label>
                    <input type="text" name="titulo_eleicao" id="titulo_eleicao" class="form-control form-control-lg" 
                           placeholder="Ex: CIPA 2025/2026" required>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="data_inicio_eleicao" class="form-label">Data de início da eleição</label>
                        <input type="date" name="data_inicio_eleicao" id="data_inicio_eleicao" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label for="data_fim_eleicao" class="form-label">Data de fim da eleição</label>
                        <input type="date" name="data_fim_eleicao" id="data_fim_eleicao" class="form-control" required>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="descricao_eleicao" class="form-label">Descrição (opcional)</label>
                    <textarea name="descricao_eleicao" id="descricao_eleicao" class="form-control" rows="3"></textarea>
                </div>

                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" name="permite_voto_branco" id="permite_voto_branco">
                    <label class="form-check-label" for="permite_voto_branco">
                        Permitir voto em branco
                    </label>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="Eleicoes.php" class="btn btn-secondary btn-lg">
                        <i class="fas fa-arrow-left"></i> Voltar
                    </a>
                    <div>
                        <button type="button" class="btn btn-outline-primary btn-lg me-2" disabled>
                            Avançar <i class="fas fa-arrow-right"></i>
                        </button>
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fas fa-save"></i> Salvar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>