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
        <title>Sobre</title>
     <meta name="author" content="Alexandre Ricardo Custódio de Souza">
    <meta name="keywords" content="Security Misconfiguration, Segurança da Informação, FATEC-PP, Professora ana Carolina Nicolossi">
    <link rel="stylesheet" href="estilo_index.css">
</head>
<body>

    <h1 class='$classe'>Security Misconfiguration A05:2021</h1>

    <div class="menu">
        <a class="bloco" href="">Grupo 5</a>
        <a class="bloco" href="">Alexandre Custódio<br>
        Rafael Daun<br>
        Rejane klebis<br>
        Tiago de Almeida</a>

        <a class="bloco" href="">Disciplina: Segurança da Informação<br>
        Professora Ana Carolina Nicolosi R. Gracioso<br>
        FATEC Presidente Prudente - SP - 2025<br></a>

        <a class="bloco" href="index.php">Voltar</a>
    </div>

    <footer>
        <p>&copy; <span><?= date('Y') ?></span> - Testes de Segurança OWASP A05:2021</p>
    </footer>

</body>
</html>
