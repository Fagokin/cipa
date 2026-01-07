<?php
require_once __DIR__ . "/../models/Usuario.php";

class Sessao {
    public static function iniciar() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function setUsuario($usuario) {
        self::iniciar();
        
        // Se for um objeto Usuario, converte para array
        if ($usuario instanceof Usuario) {
            $_SESSION['usuario'] = [
                'id_usuario' => $usuario->getIdUsuario(),
                'nome_usuario' => $usuario->getNomeUsuario(),
                'sobrenome_usuario' => $usuario->getSobrenomeUsuario(),
                'email_usuario' => $usuario->getEmailUsuario(),
                'data_nascimento_usuario' => $usuario->getDatadeNascimentoUuario(),
                'data_contratacao_usuario' => $usuario->getDataContratacaoUsuario(),
                'matricula_usuario' => $usuario->getMatriculaUsuario(),
                'cpf_usuario' => $usuario->getCpfUsuario(),
                'telefone_usuario' => $usuario->getTelefoneUsuario(),
                'ativo_usuario' => $usuario->getAtivoUsuario(),
                'adm_usuario' => $usuario->getAdmUsuario(),
                'codigo_voto_usuario' => $usuario->getCodigoVotoUsuario(),
                'ultimo_acesso_usuario' => $usuario->getUltimoAcessoUsuario()
            ];
            $_SESSION['usuario_id'] = $usuario->getIdUsuario();
            $_SESSION['usuario_adm'] = $usuario->getAdmUsuario() ? 1 : 0;
        } else {
            // Se for array, usa diretamente
            $_SESSION['usuario'] = $usuario;
            $_SESSION['usuario_id'] = $usuario['id_usuario'];
            $_SESSION['usuario_adm'] = $usuario['adm_usuario'] ?? 0;
        }
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

