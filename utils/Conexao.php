<?php
    class Conexao {
        static string $servidor = "127.0.0.1"; //localhost 
        static string $usuario = "root";
        static string $password = "";
        static string $port = "3306";
        static string $dbname = "projetocipat3";

        static function conectar() {
            try {
                $conn = new PDO(
                    "mysql:host=". self::$servidor . ";port=" . self::$port . ";dbname=" . self::$dbname,
                    self::$usuario,
                    self::$password
                );
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                return $conn;
            }catch (PDOException $e){
                error_log("Ocorreu na database " . $e->getMessage()) ;
            }
        }

    }
?>
