<?php
require_once __DIR__ . "/../repositories/EleicaoDAO.php";
require_once __DIR__ . "/../repositories/CandidatoDAO.php";

$eleicaoDAO = new EleicaoDAO();
$candidatoDAO = new CandidatoDAO();

$mensagem = "";
$erro = "";
$sucesso = false;

$idEleicao = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$eleicao = $idEleicao > 0 ? $eleicaoDAO->getPorId($idEleicao) : null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $eleicao) {
    $nome = trim($_POST['nome'] ?? '');
    $cpf = preg_replace('/[^0-9]/', '', $_POST['cpf'] ?? '');
    
    if (empty($nome) || empty($cpf)) {
        $erro = "Por favor, preencha todos os campos.";
    } elseif (strlen($cpf) != 11) {
        $erro = "CPF inválido. Deve conter 11 dígitos.";
    } else {
        $fotoCandidato = null;
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . "/../uploads/candidatos/";
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            $extensao = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
            $extensoesPermitidas = ['jpg', 'jpeg', 'png'];
            
            if (in_array($extensao, $extensoesPermitidas)) {
                $nomeArquivo = uniqid() . '.' . $extensao;
                $caminhoCompleto = $uploadDir . $nomeArquivo;
                
                if (move_uploaded_file($_FILES['foto']['tmp_name'], $caminhoCompleto)) {
                    $fotoCandidato = 'uploads/candidatos/' . $nomeArquivo;
                } else {
                    $erro = "Erro ao fazer upload da imagem.";
                }
            } else {
                $erro = "Formato de imagem inválido. Use JPG ou PNG.";
            }
        }
        
        if (empty($erro)) {
            require_once __DIR__ . "/../repositories/UsuarioDAO.php";
            $usuarioDAO = new UsuarioDAO();
            
            $usuarioExistente = $usuarioDAO->getPorCpf($cpf);
            
            if (empty($erro)) {
                if ($usuarioExistente) {
                    $idUsuario = $usuarioExistente['id_usuario'];
                } else {
                    $idUsuario = $usuarioDAO->criarUsuarioSimples($nome, $cpf);
                    if (!$idUsuario) {
                        $erro = "Erro ao criar usuário. Tente novamente.";
                    }
                }
                
                if (empty($erro)) {
                    $dadosCandidato = [
                        'eleicao_fk' => $idEleicao,
                        'funcionario_fk' => $idUsuario,
                        'numero_candidato' => 0,
                        'foto_candidato' => $fotoCandidato
                    ];
                    
                    $idCandidato = $candidatoDAO->adicionar($dadosCandidato);
                    
                    if ($idCandidato) {
                        $sucesso = true;
                        $mensagem = "Candidatura realizada com sucesso!";
                    } else {
                        $erro = "Erro ao registrar candidatura. Tente novamente.";
                    }
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
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Candidatar-se à Eleição</h4>
                </div>
                <div class="card-body">
                    <?php if ($eleicao): ?>
                        <h5 class="mb-3"><?= htmlspecialchars($eleicao['titulo_eleicao']) ?></h5>
                    <?php endif; ?>

                    <?php if ($sucesso): ?>
                        <div class="alert alert-success">
                            <strong>Sucesso!</strong> <?= $mensagem ?>
                            <br><br>
                            <a href="Cronograma.php?id=<?= $idEleicao ?>" class="btn btn-primary">Voltar</a>
                        </div>
                    <?php else: ?>
                        <?php if ($erro): ?>
                            <div class="alert alert-danger">
                                <?= htmlspecialchars($erro) ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label for="nome" class="form-label">Nome Completo <span class="text-danger">*</span></label>
                                <input type="text" name="nome" id="nome" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="cpf" class="form-label">CPF <span class="text-danger">*</span></label>
                                <input type="text" name="cpf" id="cpf" class="form-control" 
                                       placeholder="000.000.000-00" required>
                            </div>

                            <div class="mb-3">
                                <label for="foto" class="form-label">Foto</label>
                                <input type="file" name="foto" id="foto" class="form-control" 
                                       accept="image/jpeg,image/png">
                                <small class="form-text text-muted">Formatos: JPG, PNG</small>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-success btn-lg">
                                    Confirmar Candidatura
                                </button>
                                <a href="Cronograma.php?id=<?= $idEleicao ?>" class="btn btn-secondary">
                                    Voltar
                                </a>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
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
