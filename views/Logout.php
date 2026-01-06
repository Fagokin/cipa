<?php
require_once __DIR__ . "/../utils/Sessao.php";

Sessao::logout();
header("Location: Login.php");
exit;
?>

