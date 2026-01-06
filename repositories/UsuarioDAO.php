<?php
    require_once __DIR__ . "/../utils/Conexao.php";
    require_once __DIR__ ."/../models/Usuario.php";


    class UsuarioDAO {
        private $pdo;
        public function __construct() {
            $this->pdo = Conexao::conectar();
        }

    public function criarUsuarioDAO(Usuario $usuario) {
    $sql = "INSERT INTO usuario (
                nome_usuario, sobrenome_usuario, email_usuario, senha_usuario,
                data_nascimento_usuario, data_contratacao_usuario,
                matricula_usuario, cpf_usuario, telefone_usuario,
                ativo_usuario, adm_usuario
             ) VALUES (
                :nome, :sobrenome, :email, :senha,
                :nasc, :contrat, :matric, :cpf, :tel,
                :ativo, :adm
             )";

    $stmt = $this->pdo->prepare($sql);
    
    $stmt->execute([
        ':nome'     => $usuario->getNomeUsuario(),
        ':sobrenome'=> $usuario->getSobrenomeUsuario(),
        ':email'    => $usuario->getEmailUsuario() ?: null,
        ':senha'    => $usuario->getSenhaUsuario(), 
        ':nasc'     => $usuario->getDatadeNascimentoUuario(),
        ':contrat'  => $usuario->getDataContratacaoUsuario(),
        ':matric'   => $usuario->getMatriculaUsuario(),
        ':cpf'      => $usuario->getCpfUsuario(),
        ':tel'      => $usuario->getTelefoneUsuario() ?: null,
        ':ativo'    => $usuario->getAtivoUsuario() ? 1 : 0,
        ':adm'      => $usuario->getAdmUsuario() ? 1 : 0,
    ]);

    return true;

    } 
 public function listarTodos() {
    $sql = "SELECT id_usuario, nome_usuario, sobrenome_usuario, email_usuario,
                   data_nascimento_usuario, data_contratacao_usuario,
                   matricula_usuario, telefone_usuario, ativo_usuario
            FROM usuario ORDER BY nome_usuario";
    $stmt = $this->pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function getUsuarioPorId(int $id) {
        $sql = "SELECT * FROM usuario WHERE id_usuario = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function atualizar(Usuario $usuario) {
    try {
        $sql = "UPDATE usuario SET
                    nome_usuario = :nome,
                    sobrenome_usuario = :sobrenome,
                    email_usuario = :email,
                    data_nascimento_usuario = :nascimento,
                    data_contratacao_usuario = :contratacao,
                    matricula_usuario = :matricula,
                    cpf_usuario = :cpf,
                    telefone_usuario = :telefone,
                    ativo_usuario = :ativo,
                    adm_usuario = :adm
                    " . (!empty($usuario->getSenhaUsuario()) ? ", senha_usuario = :senha" : "") . "
                WHERE id_usuario = :id";

        $stmt = $this->pdo->prepare($sql);

        $params = [
            ':nome'        => $usuario->getNomeUsuario(),
            ':sobrenome'   => $usuario->getSobrenomeUsuario(),
            ':email'       => $usuario->getEmailUsuario() ?: null,
            ':nascimento'  => $usuario->getDatadeNascimentoUuario(),
            ':contratacao' => $usuario->getDataContratacaoUsuario(),
            ':matricula'   => $usuario->getMatriculaUsuario(),
            ':cpf'         => $usuario->getCpfUsuario(),
            ':telefone'    => $usuario->getTelefoneUsuario() ?: null,
            ':ativo'       => $usuario->getAtivoUsuario() ? 1 : 0,
            ':adm'         => $usuario->getAdmUsuario() ? 1 : 0,
            ':id'          => $usuario->getIdUsuario()
        ];

        if (!empty($usuario->getSenhaUsuario())) {
            $params[':senha'] = password_hash($usuario->getSenhaUsuario(), PASSWORD_BCRYPT);
        }

        $stmt->execute($params);
        return true;
    } catch (PDOException $e) {
        echo "Erro ao atualizar: " . $e->getMessage();
        return false;
        return false;
    }
}



public function excluir($id) {
    $sql = "DELETE FROM usuario WHERE id_usuario = :id";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([':id' => $id]);
}
        
    }
?>