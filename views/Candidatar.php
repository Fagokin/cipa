<?php
require_once __DIR__ . "/../repositories/UsuarioDAO.php";
require_once __DIR__ . "/../repositories/EleicaoDAO.php";
require_once __DIR__ . "/../repositories/CandidatoDAO.php";
require_once __DIR__ . "/../models/Usuario.php";

$usuarioDAO = new UsuarioDAO();
$eleicaoDAO = new EleicaoDAO();
$candidatoDAO = new CandidatoDAO();

$mensagem = "";
$erro = "";
$sucesso = false;

$idEleicao = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$eleicao = $idEleicao > 0 ? $eleicaoDAO->getPorId($idEleicao) : null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $eleicao) {
    $cpf = preg_replace('/[^0-9]/', '', $_POST['cpf'] ?? '');
    $matricula = trim($_POST['matricula'] ?? '');
    $numeroCandidato = (int)($_POST['numero_candidato'] ?? 0);
    $senha = $_POST['senha'] ?? '';
    $senhaConfirmar = $_POST['senha_confirmar'] ?? '';
    
    // Validações
    if (empty($cpf) || empty($matricula) || $numeroCandidato <= 0) {
        $erro = "Por favor, preencha todos os campos obrigatórios.";
    } elseif (empty($senha) || strlen($senha) < 6) {
        $erro = "A senha deve ter pelo menos 6 caracteres.";
    } elseif ($senha !== $senhaConfirmar) {
        $erro = "As senhas não coincidem.";
    } else {
        // Verifica se usuário existe
        $usuario = $usuarioDAO->validarCpfMatricula($cpf, $matricula);
        
        if (!$usuario) {
            $erro = "CPF e/ou matrícula não encontrados ou usuário inativo.";
        } else {
            // Verifica se número já existe
            if ($candidatoDAO->numeroJaExiste($numeroCandidato, $idEleicao)) {
                $erro = "Este número de candidato já está em uso.";
            } else {
                // Upload da foto
                $fotoCandidato = null;
                if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
                    $uploadDir = __DIR__ . "/../uploads/candidatos/";
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }
                    
                    $extensao = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
                    $nomeArquivo = uniqid() . '.' . $extensao;
                    $caminhoCompleto = $uploadDir . $nomeArquivo;
                    
                    if (move_uploaded_file($_FILES['foto']['tmp_name'], $caminhoCompleto)) {
                        $fotoCandidato = 'uploads/candidatos/' . $nomeArquivo;
                    }
                }
                
                // Cria/atualiza candidato
                $dadosCandidato = [
                    'eleicao_fk' => $idEleicao,
                    'funcionario_fk' => $usuario['id_usuario'],
                    'numero_candidato' => $numeroCandidato,
                    'foto_candidato' => $fotoCandidato
                ];
                
                $idCandidato = $candidatoDAO->adicionar($dadosCandidato);
                
                if ($idCandidato) {
                    // Atualiza senha do usuário se necessário
                    if (!empty($senha)) {
                        $usuarioObj = new Usuario(
                            $usuario['nome_usuario'],
                            $usuario['sobrenome_usuario'],
                            $senha,
                            $usuario['data_nascimento_usuario'],
                            $usuario['data_contratacao_usuario'],
                            $usuario['ativo_usuario'],
                            $usuario['adm_usuario'],
                            $usuario['matricula_usuario'],
                            $usuario['cpf_usuario'],
                            $usuario['telefone_usuario'] ?? '',
                            $usuario['email_usuario'] ?? ''
                        );
                        $usuarioObj->setIdUsuario($usuario['id_usuario']);
                        $usuarioDAO->atualizar($usuarioObj);
                    }
                    
                    $sucesso = true;
                    $mensagem = "Candidatura realizada com sucesso! Você pode fazer login no sistema.";
                } else {
                    $erro = "Erro ao registrar candidatura. Tente novamente.";
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candidatar-se - Eleição CIPA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0"><i class="fas fa-user-tie"></i> Candidatar-se à Eleição CIPA</h3>
                </div>
                <div class="card-body">
                    <?php if ($eleicao): ?>
                        <h5 class="mb-3"><?= htmlspecialchars($eleicao['titulo_eleicao']) ?></h5>
                        <p class="text-muted"><?= htmlspecialchars($eleicao['descricao_eleicao'] ?? '') ?></p>
                    <?php endif; ?>

                    <?php if ($sucesso): ?>
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle fa-2x"></i>
                            <h4 class="mt-2">Candidatura realizada com sucesso!</h4>
                            <p>Você pode fazer login no sistema usando seu CPF e a senha que acabou de definir.</p>
                            <a href="Login.php" class="btn btn-primary">
                                <i class="fas fa-sign-in-alt"></i> Fazer Login
                            </a>
                        </div>
                    <?php else: ?>
                        <?php if ($erro): ?>
                            <div class="alert alert-danger alert-dismissible fade show">
                                <i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($erro) ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <form method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="cpf" class="form-label fw-bold">CPF <span class="text-danger">*</span></label>
                                <input type="text" name="cpf" id="cpf" class="form-control form-control-lg" 
                                       placeholder="000.000.000-00" required autofocus>
                            </div>

                            <div class="mb-3">
                                <label for="matricula" class="form-label fw-bold">Matrícula <span class="text-danger">*</span></label>
                                <input type="text" name="matricula" id="matricula" class="form-control form-control-lg" 
                                       placeholder="Digite sua matrícula" required>
                            </div>

                            <div class="mb-3">
                                <label for="numero_candidato" class="form-label fw-bold">Número de Candidato <span class="text-danger">*</span></label>
                                <input type="number" name="numero_candidato" id="numero_candidato" 
                                       class="form-control form-control-lg" 
                                       placeholder="Digite o número que deseja usar" 
                                       min="1" required>
                                <small class="form-text text-muted">Escolha um número único para sua candidatura.</small>
                            </div>

                            <div class="mb-3">
                                <label for="foto" class="form-label fw-bold">Foto (opcional)</label>
                                <input type="file" name="foto" id="foto" class="form-control" 
                                       accept="image/*">
                                <small class="form-text text-muted">Formatos aceitos: JPG, PNG. Tamanho máximo: 2MB.</small>
                            </div>

                            <div class="mb-3">
                                <label for="senha" class="form-label fw-bold">Definir Senha <span class="text-danger">*</span></label>
                                <input type="password" name="senha" id="senha" class="form-control form-control-lg" 
                                       placeholder="Mínimo 6 caracteres" required>
                                <small class="form-text text-muted">Esta senha será usada para fazer login no sistema.</small>
                            </div>

                            <div class="mb-4">
                                <label for="senha_confirmar" class="form-label fw-bold">Confirmar Senha <span class="text-danger">*</span></label>
                                <input type="password" name="senha_confirmar" id="senha_confirmar" 
                                       class="form-control form-control-lg" 
                                       placeholder="Digite a senha novamente" required>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-success btn-lg">
                                    <i class="fas fa-check-circle"></i> Confirmar Candidatura
                                </button>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>
            </div>

            <div class="text-center mt-3">
                <a href="Cronograma.php<?= $idEleicao ? '?id=' . $idEleicao : '' ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Voltar
                </a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Máscara para CPF
    document.getElementById('cpf')?.addEventListener('input', function(e) {
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

