<?php
require_once __DIR__ . "/../repositories/UsuarioDAO.php";
$dao = new UsuarioDAO();

$mensagem = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['excluir_id'])) {
    $id = (int)$_POST['excluir_id'];
    
    if ($dao->excluir($id)) {
        // Redireciona com parâmetro de sucesso na URL
        header("Location: ListarTabela.php?msg=sucesso");
        exit;
    } else {
        header("Location: ListarTabela.php?msg=erro");
        exit;
    }
}

if (isset($_GET['msg'])) {
    if ($_GET['msg'] === 'sucesso') {
        $mensagem = '<div class="alert alert-success alert-dismissible fade show mt-3">
                        <strong>Sucesso!</strong> Usuário apagado com sucesso!
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                     </div>';
    } elseif ($_GET['msg'] === 'erro') {
        $mensagem = '<div class="alert alert-danger alert-dismissible fade show mt-3">
                        <strong>Erro!</strong> Não foi possível apagar o usuário.
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                     </div>';
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['excluir_id'])) {
    $id = (int)$_POST['excluir_id'];
    $dao->excluir($id); 
    echo "<script>alert('Usuário apagado com sucesso!'); location.reload();</script>";
    exit;
}
$usuarios = $dao->listarTodos();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Usuários</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h1 class="mb-4">Lista de Usuários</h1>
    <a href="CriarTabela.php" class="btn btn-success mb-4">+ Novo Usuário</a>

    <table class="table table-striped table-hover">
        <thead class="table-dark">
            <tr>
                <th>Nome Completo</th>
                <th>E-mail</th>
                <th>Nascimento</th>
                <th>Contratação</th>
                <th>Matrícula</th>
                <th>Telefone</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($usuarios as $u): ?>
            <tr>
                <td><?= htmlspecialchars($u['nome_usuario'] . ' ' . $u['sobrenome_usuario']) ?></td>
                <td><?= htmlspecialchars($u['email_usuario'] ?? '-') ?></td>
                <td><?= date('d/m/Y', strtotime($u['data_nascimento_usuario'])) ?></td>
                <td><?= date('d/m/Y', strtotime($u['data_contratacao_usuario'])) ?></td>
                <td><?= htmlspecialchars($u['matricula_usuario']) ?></td>
                <td><?= htmlspecialchars($u['telefone_usuario'] ?? '-') ?></td>
                <td>
                    <!-- Botão Editar -->
                    <form method="POST" action="EditarTabela.php" style="display:inline;">
                        <input type="hidden" name="id" value="<?= $u['id_usuario'] ?>">
                        <button type="submit" class="btn btn-primary btn-sm">Editar</button>
                    </form>

                    <!-- Botão Apagar com confirmação -->
                    <form method="POST" style="display:inline;" onsubmit="return confirm('Tem certeza que deseja apagar este usuário? Esta ação não pode ser desfeita!')">
                        <input type="hidden" name="excluir_id" value="<?= $u['id_usuario'] ?>">
                        <button type="submit" class="btn btn-danger btn-sm ms-2">Apagar</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?php

    ?>
</div>
</body>
</html>