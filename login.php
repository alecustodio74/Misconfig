<?php
session_start();
require_once 'csrf.php';
require_once 'conexao.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrfToken = $_POST['csrf_token'] ?? '';
    if (!verificarTokenCSRF($csrfToken)) {
        die("Tentativa de CSRF detectada!");
    }

    $nome = $_POST['nome'] ?? '';
    $senha = $_POST['senha'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE nome = ?");
    $stmt->execute([$nome]);
    $usuario = $stmt->fetch();

    if ($usuario && password_verify($senha, $usuario['senha'])) {
        $_SESSION['logado'] = true;
        header('Location: index.php');
        exit;
    } else {
        echo "<p style='color:red;'>NOME OU SENHA INVÁLIDOS</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Login Seguro</title>
    <link rel="stylesheet" href="estilo_index.css">
</head>
<body>
    <h1>Testes de Segurança OWASP A05:2021</h1>
    <div class="login-box">
        <form method="POST">
            <input name="nome" placeholder="Nome" required>
            <input name="senha" type="password" placeholder="Senha" required>
            <input type="hidden" name="csrf_token" value="<?= gerarTokenCSRF() ?>">
            <button type="submit">Entrar</button>
        </form>
    </div>
</body>
</html>
