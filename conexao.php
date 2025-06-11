<?php
// conexao.php

// Carrega variáveis de ambiente do .env (se necessário)
$envPath = dirname(__FILE__) . '/.env';
if (file_exists($envPath)) {
    $linhas = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($linhas as $linha) {
        if (strpos(trim($linha), '#') === 0) continue;
        list($chave, $valor) = explode('=', $linha, 2);
        putenv(trim($chave) . '=' . trim($valor));
    }
}

// Conexão usando variáveis de ambiente
$host = getenv('DB_HOST') ?: 'localhost';
$db   = getenv('DB_NAME') ?: 'backup';
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASS') ?: '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("ERRO DE CONEXÃO COM O BANCO DE DADOS: " . $e->getMessage());
}
?>
