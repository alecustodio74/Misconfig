<?php
require_once 'verifica_login.php';

// Caminho da pasta de uploads
$uploads_dir = __DIR__ . '/uploads/';

// Nome do arquivo vindo por GET
if (!isset($_GET['arquivo'])) {
    die('ARQUIVO NÃO INFORMADO.');
}

$arquivo = basename($_GET['arquivo']); // evita path traversal
$caminho = $uploads_dir . $arquivo;

// Verifica se o arquivo existe e não é um script
$ext = strtolower(pathinfo($arquivo, PATHINFO_EXTENSION));
if (!file_exists($caminho) || in_array($ext, ['php', 'phar'])) {
    die('ARQUIVO INVÁLIDO OU INEXISTENTE.');
}

// Tipos de imagem suportados para exibição
$tiposImagem = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];

if (in_array($ext, $tiposImagem)) {
    // Exibe imagem inline
    $mime = mime_content_type($caminho);
    header('Content-Type: ' . $mime);
    header('Content-Length: ' . filesize($caminho));
    readfile($caminho);
    exit;
} else {
    // Força o download
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . basename($arquivo) . '"');
    header('Content-Length: ' . filesize($caminho));
    readfile($caminho);
    exit;
}
