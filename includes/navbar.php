<?php
if (!isset($usuario)) {
    require_once __DIR__ . "/../utils/Sessao.php";
    Sessao::iniciar();
    $usuario = Sessao::getUsuario();
    $isAdmin = Sessao::isAdmin();
}
?>
<nav class="navbar navbar-dark bg-primary mb-4">
    <div class="container-fluid">
        <span class="navbar-brand mb-0 h1">
            <i class="fas fa-vote-yea"></i> Sistema CIPA
        </span>
        <?php if ($usuario): ?>
            <div class="d-flex">
                <span class="navbar-text text-white me-3">
                    <i class="fas fa-user"></i> <?= htmlspecialchars($usuario['nome_usuario'] . ' ' . $usuario['sobrenome_usuario']) ?>
                </span>
                <a href="Dashboard.php" class="btn btn-outline-light btn-sm me-2">
                    <i class="fas fa-home"></i> Dashboard
                </a>
                <a href="Logout.php" class="btn btn-outline-light btn-sm">
                    <i class="fas fa-sign-out-alt"></i> Sair
                </a>
            </div>
        <?php else: ?>
            <a href="Login.php" class="btn btn-outline-light btn-sm">
                <i class="fas fa-sign-in-alt"></i> Login
            </a>
        <?php endif; ?>
    </div>
</nav>

