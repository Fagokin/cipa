<?php
require_once __DIR__ . "/../utils/Sessao.php";
Sessao::requerAdmin();
Sessao::iniciar();

require_once __DIR__ . "/../repositories/EleicaoDAO.php";
require_once __DIR__ . "/../repositories/CandidatoDAO.php";

$eleicaoDAO = new EleicaoDAO();
$candidatoDAO = new CandidatoDAO();

$dados = $_SESSION['eleicao_dados'] ?? [];
$mensagem = "";
$sucesso = false;

if (empty($dados)) {
    $mensagem = "Erro: Dados da eleição não encontrados na sessão.";
} else {
    $dadosEleicao = [
        'titulo_eleicao' => $dados['titulo_eleicao'] ?? '',
        'descricao_eleicao' => $dados['descricao_eleicao'] ?? 'Eleição CIPA',
        'data_inicio_eleicao' => $dados['data_inicio_eleicao'] ?? date('Y-m-d H:i:s'),
        'data_fim_eleicao' => $dados['data_fim_eleicao'] ?? date('Y-m-d H:i:s'),
        'permite_voto_branco' => $dados['permite_voto_branco'] ?? 0
    ];

    $id_eleicao = $eleicaoDAO->criar($dadosEleicao);

    if ($id_eleicao) {
        $candidatosAdicionados = 0;
        $candidatosIds = $dados['candidatos'] ?? [];
        $erros = [];
        
        if (empty($candidatosIds) || !is_array($candidatosIds)) {
            $mensagem = "Erro: Nenhum candidato foi selecionado.";
            $eleicaoDAO->excluir($id_eleicao);
        } else {
            require_once __DIR__ . "/../repositories/UsuarioDAO.php";
            $usuarioDAO = new UsuarioDAO();
            
            foreach ($candidatosIds as $index => $id_usuario) {
                $id_usuario = (int)$id_usuario;
                if ($id_usuario > 0) {
                    $usuario = $usuarioDAO->getUsuarioPorId($id_usuario);
                    if (!$usuario) {
                        $erros[] = "Usuário ID $id_usuario não existe";
                        continue;
                    }
                    
                    try {
                        $resultado = $candidatoDAO->adicionar([
                            'eleicao_fk' => $id_eleicao,
                            'funcionario_fk' => $id_usuario,
                            'numero_candidato' => 0,
                            'foto_candidato' => null
                        ]);
                        if ($resultado !== false && $resultado > 0) {
                            $candidatosAdicionados++;
                        } else {
                            $erros[] = "Usuário ID $id_usuario (" . ($usuario['nome_usuario'] ?? 'N/A') . ") não pôde ser adicionado. Verifique o arquivo logs/erros.log";
                        }
                    } catch (Exception $e) {
                        $erros[] = "Erro ao adicionar usuário ID $id_usuario: " . $e->getMessage();
                    }
                }
            }
            
            if ($candidatosAdicionados > 0) {
                $mensagem = "Eleição criada com sucesso! ID: $id_eleicao. Candidatos adicionados: $candidatosAdicionados";
                $sucesso = true;
                unset($_SESSION['eleicao_dados']);
            } else {
                $mensagem = "Erro: Nenhum candidato foi adicionado à eleição.";
                if (!empty($erros)) {
                    $mensagem .= " " . implode(", ", $erros);
                }
                $mensagem .= " Verifique o arquivo logs/erros.log para mais detalhes.";
                $eleicaoDAO->excluir($id_eleicao);
            }
        }
    } else {
        $mensagem = "Erro ao salvar eleição no banco de dados.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>5º passo: Finalização</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h3>Eleição de CIPA - Passo 5: Finalização</h3>
        </div>
        <div class="card-body text-center">
            <?php if ($sucesso): ?>
                <div class="alert alert-success">
                    <h4>Eleição criada com sucesso!</h4>
                    <p>Todos os dados foram salvos no sistema.</p>
                </div>
                <a href="Eleicoes.php" class="btn btn-success btn-lg">Voltar para Lista de Eleições</a>
            <?php else: ?>
                <div class="alert alert-danger">
                    <h4>Erro!</h4>
                    <p><?= $mensagem ?></p>
                </div>
                <a href="CriarEleicao1.php" class="btn btn-secondary">Tentar Novamente</a>
            <?php endif; ?>
        </div>
    </div>
</div>
</body>
</html>

