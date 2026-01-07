<?php
require_once __DIR__ . "/../repositories/UsuarioDAO.php";

$dao = new UsuarioDAO();
$mensagem = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_usuario'])) {
    $idUsuario = (int)$_POST['id_usuario'];
    
    $codigo = $dao->gerarCodigoVoto($idUsuario);
    
    if ($codigo) {
        header("Location: ListarTabela.php?msg=codigo_gerado");
        exit;
    } else {
        header("Location: ListarTabela.php?msg=codigo_erro");
        exit;
    }
} else {
    header("Location: ListarTabela.php");
    exit;
}
?>

