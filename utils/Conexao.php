<?php
    class Conexao {
        static string $servidor = "127.0.0.1";
        static string $usuario = "root";
        static string $password = "";
        static string $port = "3306";
        static string $dbname = "projetocipat3";

        static function conectar() {
            try {
                $conn = new PDO(
                    "mysql:host=". self::$servidor . ";port=" . self::$port . ";dbname=" . self::$dbname . ";charset=utf8mb4",
                    self::$usuario,
                    self::$password
                );
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
                return $conn;
            }catch (PDOException $e){
                error_log("Ocorreu na database " . $e->getMessage());
                return null;
            }
        }

    }
?>
