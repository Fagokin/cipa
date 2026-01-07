<?php
require_once __DIR__ . "/../repositories/UsuarioDAO.php";

$dao = new UsuarioDAO();
$mensagem = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_usuario'])) {
    $idUsuario = (int)$_POST['id_usuario'];
    
    // Gera o código de votação
    $codigo = $dao->gerarCodigoVoto($idUsuario);
    
    if ($codigo) {
        // Redireciona com mensagem de sucesso
        header("Location: ListarTabela.php?msg=codigo_gerado");
        exit;
    } else {
        // Redireciona com mensagem de erro
        header("Location: ListarTabela.php?msg=codigo_erro");
        exit;
    }
} else {
    // Se não foi POST ou não tem ID, redireciona para a lista
    header("Location: ListarTabela.php");
    exit;
}
?>

