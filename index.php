<?php
	require_once __DIR__ . "/utils/Conexao.php";
        // $conn = Conexao::conectar();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CIPA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Cabeçalho do site -->
    <header id="cabecalho" class="bg-primary text-white py-4">
        <div class="container">
            <h1 class="titulo-principal">Sistema CIPA</h1>
            <p class="subtitulo">Gestão de eleições e candidaturas</p>
        </div>
    </header>

    <!-- Navegação principal -->
    <nav id="navegacao-principal" class="navbar navbar-expand-lg bg-light">
        <div class="container">
            <div class="navbar-nav">
                <a class="nav-link" href="views/ListarTabela.php">→ Gerenciar Usuários</a>
                <a class="nav-link" href="views/candidatos.php">→ Candidatos</a>
                <a class="nav-link" href="views/eleicoes.php">→ Eleições</a>
                <a class="nav-link" href="views/resultados.php">→ Resultados</a>
            </div>
        </div>
    </nav>

    <!-- Seção principal -->
    <main class="container mt-5">
        <!-- Seção de início -->
        <section id="inicio" class="mb-5">
            <div class="row">
                <div class="col-md-12">
                    <h2>Bem-vindo ao Sistema CIPA</h2>
                    <p>Este sistema permite gerenciar eleições, candidatos e visualizar resultados em tempo real.</p>
                </div>
            </div>
        </section>

        <!-- Painel de informações da eleição atual -->
        <section id="info-eleicao" class="mb-5">
            <div class="card">
                <div class="card-header">
                    <h3>Eleição Atual</h3>
                </div>
                <div class="card-body">
                    <div id="dados-eleicao">
                        <p id="titulo-eleicao">Carregando título da eleição...</p>
                        <p id="data-eleicao">Data: --/--/----</p>
                        <p id="status-eleicao">Status: --</p>
                        <p id="total-candidatos">Candidatos inscritos: 0</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Lista de candidatos -->
        <section id="candidatos" class="mb-5">
            <div class="card">
                <div class="card-header">
                    <h3>Candidatos</h3>
                </div>
                <div class="card-body">
                    <div id="lista-candidatos">
                        <!-- Lista de candidatos será carregada via PHP -->
                        <div class="alert alert-info">Carregando candidatos...</div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Resultados parciais -->
        <section id="resultados" class="mb-5">
            <div class="card">
                <div class="card-header">
                    <h3>Resultados Parciais</h3>
                </div>
                <div class="card-body">
                    <div id="painel-resultados">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="total-votos">
                                    <p><strong>Total de Votos:</strong> <span id="total-votos">0</span></p>
                                    <p><strong>Votos Válidos:</strong> <span id="votos-validos">0</span></p>
                                    <p><strong>Brancos/Nulos:</strong> <span id="votos-brancos-nulos">0</span></p>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div id="ranking-candidatos">
                                    <!-- Ranking será carregado via PHP -->
                                    <p class="text-muted">Aguardando votos...</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Rodapé -->
    <footer id="rodape" class="bg-dark text-white py-3 mt-5">
        <div class="container">
            <p class="mb-0">&copy; 2025 PROJETO CIPA - SITE. Todos os direitos reservados.</p>
            <p class="mb-0">Desenvolvido para fins educacionais.</p>
        </div>
    </footer>
</body>
</html>