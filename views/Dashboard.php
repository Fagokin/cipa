<?php
require_once __DIR__ . "/../utils/Sessao.php";
require_once __DIR__ . "/../repositories/EleicaoDAO.php";

Sessao::requerLogin();
$usuario = Sessao::getUsuario();
$isAdmin = Sessao::isAdmin();

$eleicaoDAO = new EleicaoDAO();
$eleicoes = $eleicaoDAO->listarTodas();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Sistema CIPA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <nav class="navbar navbar-dark bg-primary">
        <div class="container-fluid">
            <span class="navbar-brand mb-0 h1">
                <i class="fas fa-vote-yea"></i> Sistema CIPA
            </span>
            <div class="d-flex">
                <span class="navbar-text text-white me-3">
                    <i class="fas fa-user"></i> <?= htmlspecialchars($usuario['nome_usuario'] . ' ' . $usuario['sobrenome_usuario']) ?>
                </span>
                <a href="Logout.php" class="btn btn-outline-light btn-sm">
                    <i class="fas fa-sign-out-alt"></i> Sair
                </a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-3">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <i class="fas fa-bars"></i> Menu
                    </div>
                    <div class="list-group list-group-flush">
                        <?php if ($isAdmin): ?>
                            <a href="Dashboard.php" class="list-group-item list-group-item-action active">
                                <i class="fas fa-home"></i> Dashboard
                            </a>
                            <a href="ListarTabela.php" class="list-group-item list-group-item-action">
                                <i class="fas fa-users"></i> Gerenciar Usuários
                            </a>
                            <a href="Eleicoes.php" class="list-group-item list-group-item-action">
                                <i class="fas fa-vote-yea"></i> Gerenciar Eleições
                            </a>
                            <a href="Documentos.php" class="list-group-item list-group-item-action">
                                <i class="fas fa-file-alt"></i> Gerenciar Documentos
                            </a>
                        <?php else: ?>
                            <a href="Dashboard.php" class="list-group-item list-group-item-action active">
                                <i class="fas fa-home"></i> Dashboard
                            </a>
                            <a href="Cronograma.php" class="list-group-item list-group-item-action">
                                <i class="fas fa-calendar"></i> Cronograma
                            </a>
                            <a href="Votar.php" class="list-group-item list-group-item-action">
                                <i class="fas fa-vote-yea"></i> Votar
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="col-md-9">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0"><i class="fas fa-tachometer-alt"></i> Dashboard</h4>
                    </div>
                    <div class="card-body">
                        <h5>Bem-vindo, <?= htmlspecialchars($usuario['nome_usuario']) ?>!</h5>
                        <p class="text-muted">Selecione uma opção no menu para começar.</p>

                        <?php if ($isAdmin): ?>
                            <div class="row mt-4">
                                <div class="col-md-4">
                                    <div class="card bg-primary text-white">
                                        <div class="card-body text-center">
                                            <i class="fas fa-users fa-3x mb-2"></i>
                                            <h5>Usuários</h5>
                                            <a href="ListarTabela.php" class="btn btn-light btn-sm">Gerenciar</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card bg-success text-white">
                                        <div class="card-body text-center">
                                            <i class="fas fa-vote-yea fa-3x mb-2"></i>
                                            <h5>Eleições</h5>
                                            <a href="Eleicoes.php" class="btn btn-light btn-sm">Gerenciar</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card bg-info text-white">
                                        <div class="card-body text-center">
                                            <i class="fas fa-file-alt fa-3x mb-2"></i>
                                            <h5>Documentos</h5>
                                            <a href="Documentos.php" class="btn btn-light btn-sm">Gerenciar</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

