<?php
require_once __DIR__ . "/../repositories/UsuarioDAO.php";
require_once __DIR__ . "/../models/Usuario.php";
$dao = new UsuarioDAO();
$usuario = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['id'])) {
    $id = $_POST['id'];
    $dados = $dao->getUsuarioPorId($id); 
    if ($dados) $usuario = $dados;
}

if (!$usuario) {
    return("Usuário não encontrado.");
}

if (isset($_POST['salvar'])) {
    $u = new Usuario(
        $_POST['nome'], $_POST['sobrenome'], $_POST['senha'] ?? '',
        $_POST['nascimento'], $_POST['contratacao'],
        isset($_POST['ativo']), isset($_POST['adm']),
        $_POST['matricula'], $_POST['cpf'],
        $_POST['telefone'] ?? '', $_POST['email'] ?? ''
    );
    $u->setIdUsuario($_POST['id']);
    $dao->atualizar($u);
}

if (!empty($_POST['ajax']) && $_POST['ajax'] === 'salvar') {
    $u = new Usuario(
        $_POST['nome'],
        $_POST['sobrenome'],
        $_POST['senha'] ?? '',
        $_POST['nascimento'],
        $_POST['contratacao'],
        !empty($_POST['ativo']),
        !empty($_POST['adm']),
        $_POST['matricula'],
        $_POST['cpf'],
        $_POST['telefone'] ?? '',
        $_POST['email'] ?? ''
    );
    $u->setIdUsuario($_POST['id']);

    if ($dao->atualizar($u)) {  
        echo json_encode(['status' => 'success', 'msg' => 'Usuário atualizado com sucesso!']);
    } else {
        echo json_encode(['status' => 'error', 'msg' => 'Erro ao atualizar.']);
    }
    exit;
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Editar Usuário</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="bg-light">

<div class="container mt-5">
    <h1 class="mb-4">Editar Usuário</h1>
    <a href="ListarTabela.php" class="btn btn-secondary mb-4">← Voltar para lista</a>

    <div id="mensagem"></div>

    <form id="formEditar">
        <input type="hidden" name="id" value="<?= $usuario['id_usuario'] ?>">
        <input type="hidden" name="ajax" value="salvar">

        <div class="row g-3">
            <div class="col-md-6">
                <label>Nome</label>
                <input type="text" name="nome" class="form-control" value="<?= htmlspecialchars($usuario['nome_usuario']) ?>" required>
            </div>
            <div class="col-md-6">
                <label>Sobrenome</label>
                <input type="text" name="sobrenome" class="form-control" value="<?= htmlspecialchars($usuario['sobrenome_usuario']) ?>" required>
            </div>

            <div class="col-md-6">
                <label>E-mail</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($usuario['email_usuario'] ?? '') ?>">
            </div>
            <div class="col-md-6">
                <label>Nova senha (deixe em branco para manter)</label>
                <input type="password" name="senha" class="form-control" placeholder="Nova senha">
            </div>

            <div class="col-md-4">
                <label>Data Nascimento</label>
                <input type="date" name="nascimento" class="form-control" value="<?= $usuario['data_nascimento_usuario'] ?>" required>
            </div>
            <div class="col-md-4">
                <label>Data Contratação</label>
                <input type="date" name="contratacao" class="form-control" value="<?= $usuario['data_contratacao_usuario'] ?>" required>
            </div>
            <div class="col-md-4">
                <label>Matrícula</label>
                <input type="text" name="matricula" class="form-control" value="<?= htmlspecialchars($usuario['matricula_usuario']) ?>" required>
            </div>

            <div class="col-md-6">
                <label>CPF</label>
                <input type="text" name="cpf" class="form-control" value="<?= htmlspecialchars($usuario['cpf_usuario']) ?>" required>
            </div>
            <div class="col-md-6">
                <label>Telefone</label>
                <input type="text" name="telefone" class="form-control" value="<?= htmlspecialchars($usuario['telefone_usuario'] ?? '') ?>">
            </div>
        </div>

        <div class="mt-4">
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" name="ativo" <?= $usuario['ativo_usuario'] ? 'checked' : '' ?>>
                <label class="form-check-label">Ativo</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" name="adm" <?= $usuario['adm_usuario'] ? 'checked' : '' ?>>
                <label class="form-check-label">Administrador</label>
            </div>
        </div>

        <button type="submit" class="btn btn-success btn-lg mt-4">Salvar Alterações</button>
    </form>
</div>

<script>
document.getElementById('formEditar').addEventListener('submit', function(e) {
    e.preventDefault(); 

    const formData = new FormData(this);

    fetch('', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            Swal.fire({
                title: 'Sucesso!',
                text: data.msg,
                icon: 'success',
                confirmButtonText: 'OK',
                allowOutsideClick: false
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'ListarTabela.php';
                }
            });
        } else {
            Swal.fire('Erro', data.msg, 'error');
        }
    })
    .catch(() => {
        Swal.fire('Erro', 'Falha na comunicação com o servidor.', 'error');
    });
});
</script>