<?php
class Sessao {
    public static function iniciar() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function setUsuario($usuario) {
        self::iniciar();
        $_SESSION['usuario'] = $usuario;
        $_SESSION['usuario_id'] = $usuario['id_usuario'];
        $_SESSION['usuario_adm'] = $usuario['adm_usuario'] ?? 0;
    }

    public static function getUsuario() {
        self::iniciar();
        return $_SESSION['usuario'] ?? null;
    }

    public static function isLogado() {
        self::iniciar();
        return isset($_SESSION['usuario_id']);
    }

    public static function isAdmin() {
        self::iniciar();
        return isset($_SESSION['usuario_adm']) && $_SESSION['usuario_adm'] == 1;
    }

    public static function logout() {
        self::iniciar();
        session_unset();
        session_destroy();
    }

    public static function requerLogin() {
        if (!self::isLogado()) {
            header("Location: Login.php");
            exit;
        }
    }

    public static function requerAdmin() {
        self::requerLogin();
        if (!self::isAdmin()) {
            header("Location: index.php?erro=acesso_negado");
            exit;
        }
    }
}
?>

