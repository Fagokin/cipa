<?php
    class Conexao {
        static string $servidor = "127.0.0.1"; //localhost 
        static string $usuario = "root";
        static string $password = "4237";
        static string $port = "3306";
        static string $dbname = "projetocipaT3";

        static function conectar(): PDO {
            try {
                $conn = new PDO(
                    "mysql:host=". self::$servidor . ";port=" . self::$port . ";dbname=" . self::$dbname,
                    self::$usuario,
                    self::$password,
                    [
		    	PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
			PDO::ATTR_EMULATE_PREPARES   => false,
			PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
			]
                );
                return $conn;
        } catch (PDOException $e) {
            // ✅ Log detalhado + disparar exceção (não esconder erro!)
            $msg = "Erro na conexão com o banco de dados: " . $e->getMessage() .
                   " (DSN: mysql:host=" . self::$servidor . ";dbname=" . self::$dbname . ")";
            error_log($msg);
            
            // ⚠️ Em desenvolvimento, você pode querer ver o erro:
            if (defined('DEBUG') && DEBUG) {
                die("<pre>❌ ERRO DE CONEXÃO:\n" . htmlspecialchars($msg) . "</pre>");
            }
            
            // ✅ Em produção, relança ou trata de forma amigável
            throw new RuntimeException("Falha ao conectar ao banco de dados", 0, $e);
        }
    }
}
?>
