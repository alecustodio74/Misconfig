<?php
//require_once 'verifica_login.php';
header("Content-Security-Policy: default-src 'self'; script-src 'self'; style-src 'self'; img-src 'self' data:; object-src 'none'; base-uri 'self'; frame-ancestors 'none';");

$tempo_refresh = 5;
// Define o cabeçalho Refresh
header("Refresh: $tempo_refresh; url=" . $_SERVER['PHP_SELF']);

$base = __DIR__;
$baseUrl = dirname($_SERVER['SCRIPT_NAME']);

// Verificações automáticas
$fases = [
    'PHPINFO.PHP ACESSÍVEL' => function () use ($base) {
    $arquivo = "$base/phpinfo.php";
    // Se o arquivo não existe, considera como corrigido (nada a expor)
    if (!file_exists($arquivo)) return true;
    // Lê o conteúdo e verifica se há proteção com require do verifica_login
    $conteudo = file_get_contents($arquivo);
     // Remove comentários de linha e bloco
            $semComentarios = preg_replace([
                '/\/\/.*$/m',               // remove // até o fim da linha
                '/\/\*.*?\*\//s',           // remove blocos /* ... */
                '/#.*$/m'                   // remove comentários com #
            ], '', $conteudo);

    // Verifica se o require_once está presente e não comentado
    return preg_match("/require_once\s*\(?['\"]verifica_login\.php['\"]\)?\s*;/i", $semComentarios);
    //return strpos($conteudo, "require_once 'verifica_login.php'") !== false;
    //return strpos($conteudo, "require_once 'verifica_login.php'") !== false ||
    //       (strpos($conteudo, 'session_start') !== false &&
    //        strpos($conteudo, '$_SESSION') !== false);
    },

    'BACKUP.SQL VISÍVEL VIA URL' => function () use ($base) {
        return !file_exists("$base/backup.sql");
    },

    'UPLOADS EXECUTANDO SCRIPTS PHP' => function () use ($base) {
        $htaccessPath = "$base/uploads/.htaccess";
        if (!file_exists($htaccessPath)) return false;
        $conteudo = file_get_contents($htaccessPath);
        return strpos($conteudo, 'php_flag engine off') !== false &&
               strpos($conteudo, 'Require all denied') !== false;
    },

    'ACESSO DIRETO AO /ADMIN SEM LOGIN' => function () use ($base) {
        $adminPath = "$base/admin/painel.php";
        if (!file_exists($adminPath)) return false;
        $conteudo = file_get_contents($adminPath);
        return strpos($conteudo, "require_once '../verifica_login.php'") !== false ||
               (strpos($conteudo, 'session_start') !== false &&
                strpos($conteudo, '$_SESSION') !== false);
    },

    'CONFIG.PHP EXPÕE CREDENCIAIS' => function () use ($base) {
        $configPath = "$base/config.php";
        if (!file_exists($configPath)) return false;
        $config = file_get_contents($configPath);
        return strpos($config, 'getenv') !== false &&
               strpos($config, 'DB_') !== false;
    },

    'CABEÇALHOS DE SEGURANÇA AUSENTES' => function () use ($base) {
        $htaccessPath = "$base/.htaccess";
        if (!file_exists($htaccessPath)) return false;
        $ht = file_get_contents($htaccessPath);
        return strpos($ht, "X-Content-Type-Options") !== false &&
               strpos($ht, "Content-Security-Policy") !== false &&
               strpos($ht, "Permissions-Policy") !== false;
    },

    'LOGIN.PHP SEM PROTEÇÃO REAL' => function () use ($base) {
        $loginPath = "$base/login.php";
        if (!file_exists($loginPath)) return false;
        $conteudo = file_get_contents($loginPath);
        return strpos($conteudo, 'session_start') !== false &&
               strpos($conteudo, '$_SERVER[\'REQUEST_METHOD\']') !== false &&
               (strpos($conteudo, '$_POST') !== false || strpos($conteudo, 'filter_input') !== false);
    }
];

$links = [
    'PHPINFO.PHP ACESSÍVEL' => "$baseUrl/phpinfo.php",
    'BACKUP.SQL VISÍVEL VIA URL' => "$baseUrl/backup.sql",
    'ACESSO DIRETO AO /ADMIN SEM LOGIN' => "$baseUrl/admin/painel.php",
    'CONFIG.PHP EXPÕE CREDENCIAIS' => "$baseUrl/config.php",
    'LOGIN.PHP SEM PROTEÇÃO REAL' => "$baseUrl/login.php"
];

$total = count($fases);
$corrigidas = 0;
foreach ($fases as $descricao => $verifica) {
    if ($verifica()) $corrigidas++;
}
$percentual = round(($corrigidas / $total) * 100);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Status de Segurança - OWASP A05</title>
    <link rel="stylesheet" href="estilo_ss.css">
</head>
<body>

    <h1>Status de Segurança — OWASP A05:2021</h1>

    <div class="status-geral">
        <?= $corrigidas ?> de <?= $total ?> vulnerabilidades corrigidas (<?= $percentual ?>%)
    </div>

    <div class="barra-container">
        <div class="barra-progresso"><?= $percentual ?>%</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>FALHA IDENTIFICADA</th>
                <th>STATUS</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($fases as $descricao => $verifica): ?>
                <?php
                    $ok = $verifica();
                    $classe = $ok ? 'corrigido' : 'vulneravel';
                ?>
                <tr>
                    <td><?= $descricao ?></td>
                    <td class="<?= $classe ?>">
                        <?= $ok ? 'CORRIGIDO' : 'VULNERÁVEL' ?>
                        <?php if (!$ok && isset($links[$descricao])): ?>
                            <span class="link-vulneravel">
                                <a href="<?= $links[$descricao] ?>" target="_blank">ABRIR ARQUIVO</a>
                            </span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <footer>
        <p>&copy; <span><?= date('Y') ?></span> - Testes de Segurança OWASP A05</p>
    </footer>

</body>
</html>