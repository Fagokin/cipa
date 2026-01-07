<?php
require_once __DIR__ . "/../repositories/UsuarioDAO.php";
require_once __DIR__ . "/../utils/Sessao.php";

Sessao::iniciar();

if (Sessao::isLogado()) {
    header("Location: Dashboard.php");
    exit;
}

$mensagem = "";
$erro = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cpf = preg_replace('/[^0-9]/', '', $_POST['cpf'] ?? '');
    $senha = $_POST['senha'] ?? '';

    if (empty($cpf) || empty($senha)) {
        $erro = "Por favor, preencha CPF e senha.";
    } else {
        $usuarioDAO = new UsuarioDAO();
        $usuario = $usuarioDAO->login($cpf, $senha);

        if ($usuario) {
            Sessao::setUsuario($usuario);
            header("Location: Dashboard.php");
            exit;
        } else {
            $erro = "CPF ou senha incorretos, ou usuário inativo.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema CIPA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card login-card">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <i class="fas fa-vote-yea fa-3x text-primary mb-3"></i>
                            <h2 class="fw-bold">Sistema CIPA</h2>
                            <p class="text-muted">Acesso ao sistema</p>
                        </div>

                        <?php if ($erro): ?>
                            <div class="alert alert-danger alert-dismissible fade show">
                                <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($erro) ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <form method="POST">
                            <div class="mb-3">
                                <label for="cpf" class="form-label fw-bold">
                                    <i class="fas fa-id-card"></i> CPF
                                </label>
                                <input type="text" 
                                       name="cpf" 
                                       id="cpf" 
                                       class="form-control form-control-lg" 
                                       placeholder="000.000.000-00"
                                       required
                                       autofocus>
                            </div>

                            <div class="mb-4">
                                <label for="senha" class="form-label fw-bold">
                                    <i class="fas fa-lock"></i> Senha
                                </label>
                                <input type="password" 
                                       name="senha" 
                                       id="senha" 
                                       class="form-control form-control-lg" 
                                       placeholder="Digite sua senha"
                                       required>
                            </div>

                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-sign-in-alt"></i> Entrar
                                </button>
                            </div>

                            <div class="text-center">
                                <a href="RecuperarSenha.php" class="text-decoration-none">
                                    <i class="fas fa-key"></i> Esqueci minha senha
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="text-center mt-3">
                    <a href="index.php" class="text-white text-decoration-none">
                        <i class="fas fa-arrow-left"></i> Voltar ao início
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('cpf').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length <= 11) {
                value = value.replace(/(\d{3})(\d)/, '$1.$2');
                value = value.replace(/(\d{3})(\d)/, '$1.$2');
                value = value.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
                e.target.value = value;
            }
        });
    </script>
</body>
</html>

