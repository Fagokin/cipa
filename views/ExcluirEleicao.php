<?php
require_once __DIR__ . "/../repositories/EleicaoDAO.php";

$eleicaoDAO = new EleicaoDAO();

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    
    if ($eleicaoDAO->excluir($id)) {
        header("Location: Eleicoes.php?msg=sucesso");
        exit;
    } else {
        header("Location: Eleicoes.php?msg=erro");
        exit;
    }
} else {
    header("Location: Eleicoes.php");
    exit;
}
?>
