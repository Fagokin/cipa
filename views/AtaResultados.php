<?php
require_once __DIR__ . "/../utils/Sessao.php";
require_once __DIR__ . "/../repositories/EleicaoDAO.php";
require_once __DIR__ . "/../repositories/VotoDAO.php";
require_once __DIR__ . "/../repositories/CandidatoDAO.php";

Sessao::requerAdmin();

$eleicaoDAO = new EleicaoDAO();
$votoDAO = new VotoDAO();
$candidatoDAO = new CandidatoDAO();

$idEleicao = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$eleicao = $idEleicao > 0 ? $eleicaoDAO->getPorId($idEleicao) : null;

if (!$eleicao) {
    header("Location: Eleicoes.php");
    exit;
}

$resultados = $votoDAO->getResultadosEleicao($idEleicao);
$candidatos = $candidatoDAO->listarPorEleicao($idEleicao);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ata de Resultados - <?= htmlspecialchars($eleicao['titulo_eleicao']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        @media print {
            .no-print { display: none; }
            body { margin: 0; }
        }
        .ata-header {
            border-bottom: 3px solid #000;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
    <div class="container mt-4 mb-4">
        <div class="no-print mb-3">
            <a href="Eleicoes.php" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Voltar
            </a>
            <button onclick="window.print()" class="btn btn-primary">
                <i class="fas fa-print"></i> Imprimir
            </button>
        </div>

        <div class="card shadow">
            <div class="card-body">
                <div class="ata-header text-center">
                    <h2 class="mb-2">ATA DE RESULTADOS DA ELEIÇÃO</h2>
                    <h4><?= htmlspecialchars($eleicao['titulo_eleicao']) ?></h4>
                    <p class="mb-0">
                        <strong>Período:</strong> 
                        <?= date('d/m/Y', strtotime($eleicao['data_inicio_eleicao'])) ?> a 
                        <?= date('d/m/Y', strtotime($eleicao['data_fim_eleicao'])) ?>
                    </p>
                    <p class="mb-0">
                        <strong>Data de geração:</strong> <?= date('d/m/Y H:i:s') ?>
                    </p>
                </div>

                <div class="mb-4">
                    <h5>1. DADOS DA ELEIÇÃO</h5>
                    <table class="table table-bordered">
                        <tr>
                            <th width="30%">Título</th>
                            <td><?= htmlspecialchars($eleicao['titulo_eleicao']) ?></td>
                        </tr>
                        <tr>
                            <th>Descrição</th>
                            <td><?= htmlspecialchars($eleicao['descricao_eleicao'] ?? 'Não informado') ?></td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td><strong><?= htmlspecialchars($eleicao['status_eleicao']) ?></strong></td>
                        </tr>
                        <tr>
                            <th>Total de Votos</th>
                            <td><strong><?= $resultados['total_votos'] ?? 0 ?></strong></td>
                        </tr>
                    </table>
                </div>

                <div class="mb-4">
                    <h5>2. CANDIDATOS E RESULTADOS</h5>
                    <table class="table table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>Nº</th>
                                <th>Candidato</th>
                                <th>Status</th>
                                <th class="text-center">Total de Votos</th>
                                <th class="text-center">Percentual</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $totalVotos = $resultados['total_votos'] ?? 0;
                            foreach ($resultados['candidatos'] ?? [] as $cand): 
                                $percentual = $totalVotos > 0 ? ($cand['total_votos'] / $totalVotos) * 100 : 0;
                            ?>
                                <tr>
                                    <td><strong><?= $cand['numero_candidato'] ?></strong></td>
                                    <td><?= htmlspecialchars($cand['nome_usuario'] . ' ' . $cand['sobrenome_usuario']) ?></td>
                                    <td>
                                        <?php 
                                        $candidato = array_filter($candidatos, fn($c) => $c['numero_candidato'] == $cand['numero_candidato']);
                                        $candidato = reset($candidato);
                                        echo htmlspecialchars($candidato['status_candidato'] ?? 'N/A');
                                        ?>
                                    </td>
                                    <td class="text-center"><strong><?= $cand['total_votos'] ?></strong></td>
                                    <td class="text-center"><?= number_format($percentual, 2) ?>%</td>
                                </tr>
                            <?php endforeach; ?>
                            
                            <?php if ($resultados['branco_nulo']['quantidade_branco'] > 0 || $resultados['branco_nulo']['quantidade_nulo'] > 0): ?>
                                <tr>
                                    <td colspan="3"><strong>Votos em Branco</strong></td>
                                    <td class="text-center"><strong><?= $resultados['branco_nulo']['quantidade_branco'] ?? 0 ?></strong></td>
                                    <td class="text-center">
                                        <?= $totalVotos > 0 ? number_format((($resultados['branco_nulo']['quantidade_branco'] ?? 0) / $totalVotos) * 100, 2) : 0 ?>%
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3"><strong>Votos Nulos</strong></td>
                                    <td class="text-center"><strong><?= $resultados['branco_nulo']['quantidade_nulo'] ?? 0 ?></strong></td>
                                    <td class="text-center">
                                        <?= $totalVotos > 0 ? number_format((($resultados['branco_nulo']['quantidade_nulo'] ?? 0) / $totalVotos) * 100, 2) : 0 ?>%
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="mb-4">
                    <h5>3. RESUMO</h5>
                    <ul>
                        <li>Total de candidatos: <?= count($resultados['candidatos'] ?? []) ?></li>
                        <li>Total de votos válidos: <?= $totalVotos ?></li>
                        <li>Votos em branco: <?= $resultados['branco_nulo']['quantidade_branco'] ?? 0 ?></li>
                        <li>Votos nulos: <?= $resultados['branco_nulo']['quantidade_nulo'] ?? 0 ?></li>
                    </ul>
                </div>

                <div class="text-center mt-5">
                    <p>_________________________________</p>
                    <p><strong>Assinatura do Responsável</strong></p>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

