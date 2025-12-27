<?php
define('DEBUG', true); // ativa exibição de erro
require_once __DIR__ . '/utils/conexao.php';

try {
    $pdo = Conexao::conectar();
    $stmt = $pdo->query("SELECT DATABASE() AS db, VERSION() AS versao");
    $row = $stmt->fetch();
    
    echo "<h2>✅ Conexão PDO bem-sucedida!</h2>";
    echo "<pre>";
    print_r($row);
    echo "</pre>";
    
    // Testa charset
    $charset = $pdo->query("SELECT @@character_set_connection AS charset")->fetch()['charset'];
    echo "Charsets: " . $charset . "\n";
    
} catch (Exception $e) {
    die("<h2>❌ Falha:</h2><pre>" . htmlspecialchars($e->getMessage()) . "</pre>");
}
?>