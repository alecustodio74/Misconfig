<?php
header("Content-Security-Policy: default-src 'self'; script-src 'self'; style-src 'self'; img-src 'self' data:; object-src 'none'; base-uri 'self'; frame-ancestors 'none';");

require_once 'verifica_login.php';
require_once 'status_seguranca_func.php';
include 'config.php';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Site Seguro</title>
    <link rel="stylesheet" href="estilo_index.css">
</head>
<body>

    <?php //Título da página
        $classe = verificaSegurancaTotal() ? 'seguro' : 'inseguro';
        $mensagem = verificaSegurancaTotal() ? 'Bem-vindo ao Site Seguro!' : 'Bem-vindo ao Site Inseguro';
        echo "<h1 class='$classe'>$mensagem</h1>";
    ?>

    <div class="menu">
        <a class="bloco" href="admin\painel.php">Painel</a>
        <a class="bloco" href="status_seguro.php" target="_blank">Status dos Testes</a>
        <a class="bloco" href="logout.php">Sair</a>
    </div>

    <footer>
        <p>&copy; <span><?= date('Y') ?></span> - Testes de Segurança OWASP A05:2021</p>
    </footer>

</body>
</html>
