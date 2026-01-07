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
                   matricula_usuario, telefone_usuario, ativo_usuario,
                   codigo_voto_usuario
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

    public function getPorCpf(string $cpf) {
        $sql = "SELECT * FROM usuario WHERE cpf_usuario = :cpf";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':cpf' => $cpf]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function criarUsuarioSimples(string $nome, string $cpf): int|false {
        try {
            $sql = "INSERT INTO usuario (nome_usuario, cpf_usuario, ativo_usuario, adm_usuario) 
                    VALUES (:nome, :cpf, 1, 0)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':nome' => $nome, ':cpf' => $cpf]);
            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            error_log("Erro ao criar usuário simples: " . $e->getMessage());
            return false;
        }
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



public function excluir($id): bool {
    try {
        $sql = "DELETE FROM usuario WHERE id_usuario = :id";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        return true;
    } catch (PDOException $e) {
        error_log("Erro ao excluir usuário: " . $e->getMessage());
        return false;
    }
}

    public function gerarCodigoVoto(int $idUsuario): string|false {
        try {
            $codigo = strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));
            
            $sqlVerifica = "SELECT id_usuario FROM usuario WHERE codigo_voto_usuario = :codigo";
            $stmtVerifica = $this->pdo->prepare($sqlVerifica);
            $stmtVerifica->execute([':codigo' => $codigo]);
            
            while ($stmtVerifica->fetch()) {
                $codigo = strtoupper(substr(md5(uniqid(rand(), true)), 0, 8));
                $stmtVerifica->execute([':codigo' => $codigo]);
            }
            
            $sql = "UPDATE usuario SET codigo_voto_usuario = :codigo WHERE id_usuario = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':codigo' => $codigo,
                ':id' => $idUsuario
            ]);
            
            return $codigo;
        } catch (PDOException $e) {
            error_log("Erro ao gerar código de votação: " . $e->getMessage());
            return false;
        }
    }

    public function validarCodigoVoto(string $codigo): array|false {
        try {
            $sql = "SELECT id_usuario, nome_usuario, sobrenome_usuario, matricula_usuario, 
                           ativo_usuario, codigo_voto_usuario, cpf_usuario
                    FROM usuario 
                    WHERE codigo_voto_usuario = :codigo AND ativo_usuario = 1";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':codigo' => $codigo]);
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $resultado ? $resultado : false;
        } catch (PDOException $e) {
            error_log("Erro ao validar código de votação: " . $e->getMessage());
            return false;
        }
    }

    public function marcarCodigoComoUsado(int $idUsuario): bool {
        try {
            $sql = "UPDATE usuario SET codigo_voto_usuario = NULL WHERE id_usuario = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id' => $idUsuario]);
            return true;
        } catch (PDOException $e) {
            error_log("Erro ao marcar código como usado: " . $e->getMessage());
            return false;
        }
    }

    public function getCodigoVoto(int $idUsuario): string|false {
        try {
            $sql = "SELECT codigo_voto_usuario FROM usuario WHERE id_usuario = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':id' => $idUsuario]);
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $resultado && !empty($resultado['codigo_voto_usuario']) 
                ? $resultado['codigo_voto_usuario'] 
                : false;
        } catch (PDOException $e) {
            error_log("Erro ao buscar código de votação: " . $e->getMessage());
            return false;
        }
    }

    public function login(string $cpf, string $senha): Usuario|false {
        try {
            $sql = "SELECT * FROM usuario WHERE cpf_usuario = :cpf AND ativo_usuario = 1";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([':cpf' => $cpf]);
            $dados = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$dados) {
                return false;
            }
            
            if (!password_verify($senha, $dados['senha_usuario'])) {
                return false;
            }
            
            $usuario = new Usuario(
                $dados['nome_usuario'],
                $dados['sobrenome_usuario'],
                '', // senha não é retornada por segurança
                $dados['data_nascimento_usuario'],
                $dados['data_contratacao_usuario'],
                (bool)$dados['ativo_usuario'],
                (bool)$dados['adm_usuario'],
                $dados['matricula_usuario'],
                $dados['cpf_usuario'],
                $dados['telefone_usuario'] ?? '',
                $dados['email_usuario'] ?? '',
                $dados['codigo_voto_usuario'] ?? '',
                $dados['id_usuario'],
                $dados['ultimo_acesso_usuario'] ?? ''
            );
            
            return $usuario;
        } catch (PDOException $e) {
            error_log("Erro ao realizar login: " . $e->getMessage());
            return false;
        }
    }
}
?>