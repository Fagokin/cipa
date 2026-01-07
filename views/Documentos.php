<?php
require_once __DIR__ . "/../utils/Sessao.php";
require_once __DIR__ . "/../repositories/DocumentoDAO.php";
require_once __DIR__ . "/../repositories/EleicaoDAO.php";

Sessao::requerAdmin();

$documentoDAO = new DocumentoDAO();
$eleicaoDAO = new EleicaoDAO();

$mensagem = "";
$eleicoes = $eleicaoDAO->listarTodas();
$documentos = $documentoDAO->listarTodos();

if (isset($_GET['msg'])) {
    if ($_GET['msg'] === 'sucesso') {
        $mensagem = '<div class="alert alert-success alert-dismissible fade show">
                        <strong>Sucesso!</strong> Documento criado com sucesso!
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                     </div>';
    } elseif ($_GET['msg'] === 'excluido') {
        $mensagem = '<div class="alert alert-success alert-dismissible fade show">
                        <strong>Sucesso!</strong> Documento excluído com sucesso!
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
    <title>Gerenciar Documentos - Sistema CIPA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <?php
    if (!isset($usuario)) {
        require_once __DIR__ . "/../utils/Sessao.php";
        Sessao::iniciar();
        $usuario = Sessao::getUsuario();
        $isAdmin = Sessao::isAdmin();
    }
    ?>
    <nav class="navbar navbar-dark bg-primary mb-4">
        <div class="container-fluid">
            <span class="navbar-brand mb-0 h1">
                <i class="fas fa-vote-yea"></i> Sistema CIPA
            </span>
            <?php if ($usuario): ?>
                <div class="d-flex">
                    <span class="navbar-text text-white me-3">
                        <i class="fas fa-user"></i> <?= htmlspecialchars($usuario['nome_usuario'] . ' ' . $usuario['sobrenome_usuario']) ?>
                    </span>
                    <a href="Dashboard.php" class="btn btn-outline-light btn-sm me-2">
                        <i class="fas fa-home"></i> Dashboard
                    </a>
                    <a href="Logout.php" class="btn btn-outline-light btn-sm">
                        <i class="fas fa-sign-out-alt"></i> Sair
                    </a>
                </div>
            <?php else: ?>
                <a href="Login.php" class="btn btn-outline-light btn-sm">
                    <i class="fas fa-sign-in-alt"></i> Login
                </a>
            <?php endif; ?>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-file-alt"></i> Gerenciar Documentos</h2>
            <a href="CriarDocumento.php" class="btn btn-success">
                <i class="fas fa-plus"></i> Novo Documento
            </a>
        </div>

        <?= $mensagem ?>

        <div class="card shadow">
            <div class="card-body">
                <?php if (empty($documentos)): ?>
                    <div class="alert alert-info text-center">
                        <i class="fas fa-info-circle fa-3x mb-3"></i>
                        <h4>Nenhum documento cadastrado</h4>
                        <p>Clique em "Novo Documento" para começar.</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Título</th>
                                    <th>Tipo</th>
                                    <th>Eleição</th>
                                    <th>Data Registro</th>
                                    <th>Período</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($documentos as $doc): ?>
                                <tr>
                                    <td><strong><?= htmlspecialchars($doc['titulo_documento']) ?></strong></td>
                                    <td>
                                        <span class="badge bg-<?= $doc['tipo_documento'] === 'edital' ? 'primary' : 'info' ?>">
                                            <?= ucfirst($doc['tipo_documento']) ?>
                                        </span>
                                    </td>
                                    <td><?= htmlspecialchars($doc['titulo_eleicao'] ?? 'Geral') ?></td>
                                    <td><?= date('d/m/Y H:i', strtotime($doc['data_registro_documento'])) ?></td>
                                    <td>
                                        <?php if ($doc['data_inicio_documento'] && $doc['data_fim_documento']): ?>
                                            <?= date('d/m/Y', strtotime($doc['data_inicio_documento'])) ?> - 
                                            <?= date('d/m/Y', strtotime($doc['data_fim_documento'])) ?>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="../uploads/documentos/<?= htmlspecialchars($doc['arquivo_documento']) ?>" 
                                           class="btn btn-sm btn-primary" download>
                                            <i class="fas fa-download"></i> Baixar
                                        </a>
                                        <a href="ExcluirDocumento.php?id=<?= $doc['id_documento'] ?>" 
                                           class="btn btn-sm btn-danger"
                                           onclick="return confirm('Tem certeza que deseja excluir este documento?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

