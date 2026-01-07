<?php
require_once __DIR__ . "/../repositories/VotoDAO.php";
require_once __DIR__ . "/../repositories/UsuarioDAO.php";
require_once __DIR__ . "/../repositories/EleicaoDAO.php";
require_once __DIR__ . "/../repositories/CandidatoDAO.php";

$votoDAO = new VotoDAO();
$usuarioDAO = new UsuarioDAO();
$eleicaoDAO = new EleicaoDAO();
$candidatoDAO = new CandidatoDAO();

$mensagem = "";
$sucesso = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idUsuario = isset($_POST['id_usuario']) ? (int)$_POST['id_usuario'] : 0;
    $idEleicao = isset($_POST['id_eleicao']) ? (int)$_POST['id_eleicao'] : 0;
    $codigoVoto = isset($_POST['codigo_voto']) ? trim($_POST['codigo_voto']) : '';
    $idCandidato = isset($_POST['id_candidato']) ? $_POST['id_candidato'] : null;

    $usuario = $usuarioDAO->validarCodigoVoto($codigoVoto);
    
    if (!$usuario || $usuario['id_usuario'] != $idUsuario) {
        $mensagem = "Código de votação inválido ou já utilizado.";
    } elseif ($votoDAO->jaVotou($idUsuario, $idEleicao)) {
        $mensagem = "Você já votou nesta eleição.";
    } else {
        if ($idCandidato === 'branco') {
            if ($votoDAO->registrarVotoBranco($idUsuario, $idEleicao)) {
                $usuarioDAO->marcarCodigoComoUsado($idUsuario);
                $sucesso = true;
            } else {
                $mensagem = "Erro ao registrar voto branco.";
            }
        } else {
            require_once __DIR__ . "/../utils/Conexao.php";
            $pdo = \Conexao::conectar();
            
            $sql = "SELECT lc.id_lista_candidato 
                    FROM lista_candidatos lc
                    JOIN candidato c ON lc.candidato_fk = c.id_candidato
                    WHERE (c.id_candidato = :id OR c.numero_candidato = :id OR lc.id_lista_candidato = :id) 
                    AND c.eleicao_fk = :eleicao AND c.ativo_candidato = 1
                    LIMIT 1";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':id' => (int)$idCandidato,
                ':eleicao' => $idEleicao
            ]);
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($resultado && isset($resultado['id_lista_candidato'])) {
                $dadosVoto = [
                    'funcionario_fk' => $idUsuario,
                    'eleicao_fk' => $idEleicao,
                    'lista_candidato_fk' => $resultado['id_lista_candidato']
                ];
                
                $resultadoVoto = $votoDAO->registrar($dadosVoto);
                if ($resultadoVoto) {
                    $sql = "UPDATE lista_candidatos 
                            SET quantidade_votos_lista_candidato = quantidade_votos_lista_candidato + 1 
                            WHERE id_lista_candidato = :id";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([':id' => $resultado['id_lista_candidato']]);
                    
                    $usuarioDAO->marcarCodigoComoUsado($idUsuario);
                    $sucesso = true;
                } else {
                    $mensagem = "Erro ao registrar voto.";
                }
            } else {
                $mensagem = "Candidato não encontrado.";
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
    <title>Processar Voto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h3 class="mb-0"><i class="fas fa-vote-yea"></i> Confirmação de Voto</h3>
                </div>
                <div class="card-body text-center">
                    <?php if ($sucesso): ?>
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle fa-3x mb-3"></i>
                            <h4>Voto registrado com sucesso!</h4>
                            <p>Seu voto foi computado corretamente.</p>
                        </div>
                        <a href="Eleicoes.php" class="btn btn-primary">Voltar para Eleições</a>
                    <?php else: ?>
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle fa-3x mb-3"></i>
                            <h4>Erro ao processar voto</h4>
                            <p><?= htmlspecialchars($mensagem) ?></p>
                        </div>
                        <a href="Eleicoes.php" class="btn btn-secondary">Voltar</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
