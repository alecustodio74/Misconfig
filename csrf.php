<?php
// csrf.php

// Gera token e salva na sessão
function gerarTokenCSRF() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// Valida token recebido com o da sessão
function verificarTokenCSRF($token) {
    return hash_equals($_SESSION['csrf_token'] ?? '', $token);
}
