<?php
require_once __DIR__ . "/../repositories/UsuarioDAO.php";
require_once __DIR__ . "/../models/Usuario.php";

$mensagem = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ativo = isset($_POST['ativo']);
    $adm   = isset($_POST['adm']);

    $usuario = new Usuario(
        $_POST['nome'] ?? '',
        $_POST['sobrenome'] ?? '',
        $_POST['senha'] ?? '',
        $_POST['nascimento'] ?? '',
        $_POST['contratacao'] ?? '',
        $ativo,
        $adm,
        $_POST['matricula'] ?? '',
        $_POST['cpf'] ?? '',
        $_POST['telefone'] ?? '',
        $_POST['email'] ?? ''
    );

    $dao = new UsuarioDAO();
    if ($dao->criarUsuarioDAO($usuario)) {
        $mensagem = "<div class='alert alert-success mt-4'><strong>Sucesso!</strong> Usuário criado com sucesso!</div>";
    } else {
        $mensagem = "<div class='alert alert-danger mt-4'><strong>Erro!</strong> Não foi possível criar o usuário.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Usuário</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h1 class="mb-4">Cadastrar Novo Usuário</h1>
    <a href="ListarTabela.php" class="btn btn-secondary mb-4">← Voltar para Lista</a>

    <?= $mensagem ?>

    <form method="POST">
        <div class="row g-3">

            <div class="col-md-6">
                <label for="nome" class="form-label">Nome <span class="text-muted">(primeiro nome do usuário)</span></label>
                <input type="text" name="nome" id="nome" class="form-control" placeholder="Ex: João" required>
            </div>
            <div class="col-md-6">
                <label for="sobrenome" class="form-label">Sobrenome <span class="text-muted">(sobrenome completo)</span></label>
                <input type="text" name="sobrenome" id="sobrenome" class="form-control" placeholder="Ex: Silva Santos" required>
            </div>

            <div class="col-md-8">
                <label for="email" class="form-label">E-mail <span class="text-muted">(opcional - usado para login ou recuperação)</span></label>
                <input type="email" name="email" id="email" class="form-control" placeholder="Ex: joao.silva@empresa.com">
            </div>

            <div class="col-md-4">
                <label for="senha" class="form-label">Senha <span class="text-muted">(mínimo 6 caracteres)</span></label>
                <input type="password" name="senha" id="senha" class="form-control" placeholder="Digite uma senha segura" required>
            </div>

            <div class="col-md-4">
                <label for="nascimento" class="form-label">Data de Nascimento <span class="text-muted">(data que a pessoa nasceu)</span></label>
                <input type="date" name="nascimento" id="nascimento" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label for="contratacao" class="form-label">Data de Contratação <span class="text-muted">(data de admissão na empresa)</span></label>
                <input type="date" name="contratacao" id="contratacao" class="form-control" required>
            </div>

            <div class="col-md-4">
                <label for="matricula" class="form-label">Matrícula <span class="text-muted">(número de identificação na empresa)</span></label>
                <input type="text" name="matricula" id="matricula" class="form-control" placeholder="Ex: 12345" required>
            </div>
            <div class="col-md-6">
                <label for="cpf" class="form-label">CPF <span class="text-muted">(apenas números)</span></label>
                <input type="text" name="cpf" id="cpf" class="form-control" placeholder="Ex: 12345678900" required>
            </div>

            <div class="col-md-6">
                <label for="telefone" class="form-label">Telefone <span class="text-muted">(opcional - com DDD)</span></label>
                <input type="text" name="telefone" id="telefone" class="form-control" placeholder="Ex: (11) 98765-4321">
            </div>
        </div>

        <div class="mt-4">
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" name="ativo" id="ativo" checked>
                <label class="form-check-label" for="ativo">
                    Usuário Ativo <span class="text-muted">(desmarque se o usuário estiver afastado ou inativo)</span>
                </label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" name="adm" id="adm">
                <label class="form-check-label" for="adm">
                    Administrador <span class="text-muted">(tem acesso total ao sistema)</span>
                </label>
            </div>
        </div>

        <div class="mt-5">
            <button type="submit" class="btn btn-success btn-lg px-5">Cadastrar Usuário</button>
        </div>
    </form>
</div>

</body>
</html>