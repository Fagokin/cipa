<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
        require_once __DIR__ . "/utils/Conexao.php";
        $conn = Conexao::conectar();
        var_dump($conn);

    ?>
</body>
</html>