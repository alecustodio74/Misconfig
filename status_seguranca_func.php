<?php
function verificaSegurancaTotal() {
    $base = __DIR__;

    $fases = [
        'PHPINFO.PHP ACESSÍVEL' => function () use ($base) {
            $arquivo = "$base/phpinfo.php";
            if (!file_exists($arquivo)) return true;
            $conteudo = file_get_contents($arquivo);
            return strpos($conteudo, "require_once 'verifica_login.php'") !== false ||
                   (strpos($conteudo, 'session_start') !== false &&
                    strpos($conteudo, '$_SESSION') !== false);
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
            $conteudo = file_get_contents($configPath);
            return strpos($conteudo, 'getenv') !== false &&
                   strpos($conteudo, 'DB_') !== false;
        },
        'CABEÇALHOS DE SEGURANÇA AUSENTES' => function () use ($base) {
            $htaccessPath = "$base/.htaccess";
            if (!file_exists($htaccessPath)) return false;
            $conteudo = file_get_contents($htaccessPath);
            return strpos($conteudo, "X-Content-Type-Options") !== false &&
                   strpos($conteudo, "Content-Security-Policy") !== false &&
                   strpos($conteudo, "Permissions-Policy") !== false;
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

    foreach ($fases as $verifica) {
        if (!$verifica()) {
            return false; // Alguma falha encontrada
        }
    }

    return true; // Tudo corrigido
}
