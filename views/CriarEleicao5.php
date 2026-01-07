<?php
require_once __DIR__ . "/../utils/Sessao.php";
Sessao::requerAdmin();

session_start();
if (!isset($_SESSION['eleicao_dados'])) {
    header("Location: CriarEleicao4.php");
    exit;
}

require_once __DIR__ . "/../repositories/EleicaoDAO.php";
require_once __DIR__ . "/../repositories/CandidatoDAO.php";

$eleicaoDAO = new EleicaoDAO();
$candidatoDAO = new CandidatoDAO();

// Salva a eleição no banco
$dados = $_SESSION['eleicao_dados'];
$dadosEleicao = [
    'titulo_eleicao' => $dados['titulo_eleicao'] ?? '',
    'descricao_eleicao' => 'Eleição CIPA',
    'data_inicio_eleicao' => $dados['data_inicio_eleicao'] ?? date('Y-m-d'),
    'data_fim_eleicao' => $dados['data_fim_eleicao'] ?? date('Y-m-d'),
    'permite_voto_branco' => 0
];

$id_eleicao = $eleicaoDAO->criar($dadosEleicao);

if ($id_eleicao) {
    // Salva candidatos
    foreach ($dados['candidatos'] ?? [] as $id_usuario) {
        $candidatoDAO->adicionar([
            'eleicao_fk' => $id_eleicao,
            'funcionario_fk' => $id_usuario,
            'numero_candidato' => 0,
            'foto_candidato' => null
        ]);
    }

    $mensagem = "Eleição criada com sucesso! ID: $id_eleicao";
    $sucesso = true;
} else {
    $mensagem = "Erro ao salvar eleição.";
    $sucesso = false;
}

// Limpa sessão
unset($_SESSION['eleicao_dados']);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>5º passo: Finalização</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h3>Eleição de CIPA - Passo 5: Finalização</h3>
        </div>
        <div class="card-body text-center">
            <?php if ($sucesso): ?>
                <div class="alert alert-success">
                    <h4>Eleição criada com sucesso!</h4>
                    <p>Todos os dados foram salvos no sistema.</p>
                </div>
                <a href="Eleicoes.php" class="btn btn-success btn-lg">Voltar para Lista de Eleições</a>
            <?php else: ?>
                <div class="alert alert-danger">
                    <h4>Erro!</h4>
                    <p><?= $mensagem ?></p>
                </div>
                <a href="CriarEleicao1.php" class="btn btn-secondary">Tentar Novamente</a>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>

