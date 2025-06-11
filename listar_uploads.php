<?php
    header("Content-Security-Policy: default-src 'self'; script-src 'self'; style-src 'self'; img-src 'self' data:; object-src 'none'; base-uri 'self'; frame-ancestors 'none';");

    require_once 'verifica_login.php';

$uploads_dir = __DIR__ . '/uploads/';
$arquivos = array_diff(scandir($uploads_dir), ['.', '..', '.htaccess']);

function obterIcone($ext) {
    return match (strtolower($ext)) {
        'pdf' => '📄',
        'doc', 'docx' => '📝',
        'xls', 'xlsx' => '📊',
        'txt' => '📃',
        default => '📁',
    };
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Uploads</title>
    <link rel="stylesheet" href="listar_uploads.css">
</head>
<body>
    <h2>Arquivos na Pasta Uploads</h2>
    <div class="galeria">
        <?php foreach ($arquivos as $arquivo): 
            $ext = strtolower(pathinfo($arquivo, PATHINFO_EXTENSION));
            $nome = htmlspecialchars($arquivo);
            $url = "download_upload.php?arquivo=" . urlencode($arquivo);
        ?>
        <div class="arquivo">
            <a href="<?= $url ?>" download>
                <?php if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])): ?>
                    <img src="uploads/<?= rawurlencode($arquivo) ?>" class="thumb" alt="<?= $nome ?>">
                <?php else: ?>
                    <div class="icone"><?= obterIcone($ext) ?></div>
                <?php endif; ?>
                <span title="<?= $nome ?>"><?= strlen($nome) > 18 ? substr($nome, 0, 15) . '...' : $nome ?></span>
            </a>
        </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
