<?php
require_once __DIR__ . "/../utils/Sessao.php";
require_once __DIR__ . "/../repositories/DocumentoDAO.php";
require_once __DIR__ . "/../repositories/EleicaoDAO.php";

Sessao::requerAdmin();

$documentoDAO = new DocumentoDAO();
$eleicaoDAO = new EleicaoDAO();

$mensagem = "";
$eleicoes = $eleicaoDAO->listarTodas();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uploadDir = __DIR__ . "/../uploads/documentos/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }
    
    $arquivoNome = null;
    if (isset($_FILES['arquivo']) && $_FILES['arquivo']['error'] === UPLOAD_ERR_OK) {
        $extensao = pathinfo($_FILES['arquivo']['name'], PATHINFO_EXTENSION);
        $nomeArquivo = uniqid() . '.' . $extensao;
        $caminhoCompleto = $uploadDir . $nomeArquivo;
        
        if (move_uploaded_file($_FILES['arquivo']['tmp_name'], $caminhoCompleto)) {
            $arquivoNome = $nomeArquivo;
        }
    }
    
    if ($arquivoNome) {
        $dados = [
            'titulo_documento' => $_POST['titulo_documento'],
            'tipo_documento' => $_POST['tipo_documento'],
            'arquivo_documento' => $arquivoNome,
            'eleicao_fk' => !empty($_POST['eleicao_fk']) ? (int)$_POST['eleicao_fk'] : null,
            'data_inicio_documento' => !empty($_POST['data_inicio_documento']) ? $_POST['data_inicio_documento'] : null,
            'data_fim_documento' => !empty($_POST['data_fim_documento']) ? $_POST['data_fim_documento'] : null
        ];
        
        if ($documentoDAO->criar($dados)) {
            header("Location: Documentos.php?msg=sucesso");
            exit;
        } else {
            $mensagem = '<div class="alert alert-danger">Erro ao criar documento.</div>';
        }
    } else {
        $mensagem = '<div class="alert alert-danger">Erro ao fazer upload do arquivo.</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Documento - Sistema CIPA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <?php include __DIR__ . '/../includes/navbar.php'; ?>

    <div class="container mt-4">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h3 class="mb-0"><i class="fas fa-file-plus"></i> Criar Documento</h3>
            </div>
            <div class="card-body">
                <?= $mensagem ?>

                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="titulo_documento" class="form-label fw-bold">Título <span class="text-danger">*</span></label>
                        <input type="text" name="titulo_documento" id="titulo_documento" 
                               class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label for="tipo_documento" class="form-label fw-bold">Tipo <span class="text-danger">*</span></label>
                        <select name="tipo_documento" id="tipo_documento" class="form-select" required>
                            <option value="">Selecione...</option>
                            <option value="edital">Edital</option>
                            <option value="ata">Ata</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="eleicao_fk" class="form-label fw-bold">Eleição (opcional)</label>
                        <select name="eleicao_fk" id="eleicao_fk" class="form-select">
                            <option value="">Geral (não vinculado a eleição específica)</option>
                            <?php foreach ($eleicoes as $e): ?>
                                <option value="<?= $e['id_eleicao'] ?>">
                                    <?= htmlspecialchars($e['titulo_eleicao']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="data_inicio_documento" class="form-label">Data de Início (opcional)</label>
                            <input type="date" name="data_inicio_documento" id="data_inicio_documento" 
                                   class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label for="data_fim_documento" class="form-label">Data de Fim (opcional)</label>
                            <input type="date" name="data_fim_documento" id="data_fim_documento" 
                                   class="form-control">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="arquivo" class="form-label fw-bold">Arquivo <span class="text-danger">*</span></label>
                        <input type="file" name="arquivo" id="arquivo" class="form-control" 
                               accept=".pdf,.doc,.docx" required>
                        <small class="form-text text-muted">Formatos aceitos: PDF, DOC, DOCX. Tamanho máximo: 10MB.</small>
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="Documentos.php" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Voltar
                        </a>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-save"></i> Salvar Documento
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

