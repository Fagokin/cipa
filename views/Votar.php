<?php
require_once __DIR__ . "/../repositories/UsuarioDAO.php";
require_once __DIR__ . "/../repositories/EleicaoDAO.php";
require_once __DIR__ . "/../repositories/CandidatoDAO.php";

$usuarioDAO = new UsuarioDAO();
$eleicaoDAO = new EleicaoDAO();
$candidatoDAO = new CandidatoDAO();

$mensagem = "";
$usuario = null;
$eleicao = null;
$candidatos = [];

// Validação do código de votação
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['codigo_voto'])) {
    $codigo = strtoupper(trim($_POST['codigo_voto']));
    $usuario = $usuarioDAO->validarCodigoVoto($codigo);
    
    if ($usuario) {
        // Código válido, busca a eleição
        $idEleicao = isset($_POST['id_eleicao']) ? (int)$_POST['id_eleicao'] : 0;
        if ($idEleicao > 0) {
            $eleicao = $eleicaoDAO->getPorId($idEleicao);
            if ($eleicao) {
                $candidatos = $candidatoDAO->listarPorEleicao($idEleicao);
            }
        }
    } else {
        $mensagem = '<div class="alert alert-danger alert-dismissible fade show">
                        <strong>Erro!</strong> Código de votação inválido ou usuário inativo.
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                     </div>';
    }
}

// Se não tem código na URL, mostra formulário de validação
if (!isset($_GET['codigo']) && !$usuario) {
    $idEleicao = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    $eleicao = $idEleicao > 0 ? $eleicaoDAO->getPorId($idEleicao) : null;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Votar na Eleição CIPA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0"><i class="fas fa-vote-yea"></i> Votação CIPA</h3>
                </div>
                <div class="card-body">
                    <?= $mensagem ?>

                    <?php if (!$usuario): ?>
                        <!-- Formulário de validação do código -->
                        <form method="POST">
                            <input type="hidden" name="id_eleicao" value="<?= $eleicao['id_eleicao'] ?? '' ?>">
                            
                            <div class="mb-4">
                                <h5 class="mb-3">Validação de Código de Votação</h5>
                                <p class="text-muted">Digite o código de votação que foi gerado para você na lista de usuários.</p>
                                
                                <label for="codigo_voto" class="form-label fw-bold">Código de Votação</label>
                                <input type="text" 
                                       name="codigo_voto" 
                                       id="codigo_voto" 
                                       class="form-control form-control-lg text-uppercase" 
                                       placeholder="Ex: A1B2C3D4" 
                                       maxlength="8"
                                       required
                                       autofocus>
                                <small class="form-text text-muted">O código deve ter 8 caracteres alfanuméricos.</small>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-check-circle"></i> Validar e Continuar
                                </button>
                            </div>
                        </form>
                    <?php else: ?>
                        <!-- Formulário de votação -->
                        <?php if ($eleicao && !empty($candidatos)): ?>
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle"></i> 
                                <strong>Código validado!</strong> Olá, <?= htmlspecialchars($usuario['nome_usuario'] . ' ' . $usuario['sobrenome_usuario']) ?>
                            </div>

                            <h5 class="mb-3"><?= htmlspecialchars($eleicao['titulo_eleicao']) ?></h5>
                            <p class="text-muted"><?= htmlspecialchars($eleicao['descricao_eleicao'] ?? '') ?></p>

                            <form method="POST" action="ProcessarVoto.php">
                                <input type="hidden" name="id_usuario" value="<?= $usuario['id_usuario'] ?>">
                                <input type="hidden" name="id_eleicao" value="<?= $eleicao['id_eleicao'] ?>">
                                <input type="hidden" name="codigo_voto" value="<?= htmlspecialchars($_POST['codigo_voto']) ?>">

                                <div class="mb-4">
                                    <label class="form-label fw-bold">Selecione seu candidato:</label>
                                    <div class="list-group">
                                        <?php foreach ($candidatos as $candidato): ?>
                                            <label class="list-group-item">
                                                <input type="radio" 
                                                       name="id_candidato" 
                                                       value="<?= $candidato['id_candidato'] ?>" 
                                                       class="form-check-input me-2" 
                                                       required>
                                                <strong><?= htmlspecialchars($candidato['nome_usuario'] . ' ' . $candidato['sobrenome_usuario']) ?></strong>
                                            </label>
                                        <?php endforeach; ?>
                                    </div>
                                </div>

                                <?php if ($eleicao['permite_voto_branco']): ?>
                                    <div class="mb-4">
                                        <label class="list-group-item">
                                            <input type="radio" 
                                                   name="id_candidato" 
                                                   value="branco" 
                                                   class="form-check-input me-2">
                                            <strong>Voto em Branco</strong>
                                        </label>
                                    </div>
                                <?php endif; ?>

                                <div class="d-grid">
                                    <button type="submit" class="btn btn-success btn-lg">
                                        <i class="fas fa-paper-plane"></i> Confirmar Voto
                                    </button>
                                </div>
                            </form>
                        <?php else: ?>
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle"></i> 
                                Eleição não encontrada ou sem candidatos cadastrados.
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>

            <div class="text-center mt-3">
                <a href="Eleicoes.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Voltar para Eleições
                </a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Converte automaticamente para maiúsculas
document.getElementById('codigo_voto')?.addEventListener('input', function(e) {
    this.value = this.value.toUpperCase().replace(/[^A-Z0-9]/g, '');
});
</script>

</body>
</html>

