<?php
header("Content-Security-Policy: default-src 'self'; script-src 'self'; style-src 'self'; img-src 'self' data:; object-src 'none'; base-uri 'self'; frame-ancestors 'none';");

require_once '../verifica_login.php';

// conexão com variáveis de ambiente
$envPath = __DIR__ . '/.env';
if (file_exists($envPath)) {
    $linhas = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($linhas as $linha) {
        if (strpos(trim($linha), '#') === 0) continue;
        list($chave, $valor) = explode('=', $linha, 2);
        putenv(trim($chave) . '=' . trim($valor));
    }
}

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

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Painel de Administração</title>
    <link rel="stylesheet" href="..\estilo_index.css">
    </head>
<body>
    <h1>Painel de Administração</h1>

    <div class="menu">
        <a class="bloco" href="..\listar_uploads.php">📁 Ver Arquivos da Pasta Uploads</a>
        <a class="bloco" href="..\index.php">🏠 Voltar para o Início</a>
        <a class="bloco" href="..\logout.php">🚪 Sair</a>
    </div>

    <footer>
        <p>&copy; <span><?= date('Y') ?></span> - Testes de Segurança OWASP A05</p>
    </footer>
</body>
</html>
