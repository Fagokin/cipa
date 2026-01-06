<?php
require_once __DIR__ . "/../utils/Sessao.php";
require_once __DIR__ . "/../repositories/DocumentoDAO.php";

Sessao::requerAdmin();

$documentoDAO = new DocumentoDAO();
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    $documento = $documentoDAO->getPorId($id);
    if ($documento) {
        // Remove arquivo físico se existir
        $arquivo = __DIR__ . "/../uploads/documentos/" . $documento['arquivo_documento'];
        if (file_exists($arquivo)) {
            unlink($arquivo);
        }
        
        $documentoDAO->excluir($id);
    }
}

header("Location: Documentos.php?msg=excluido");
exit;
?>

