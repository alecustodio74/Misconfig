<?php
    header("Content-Security-Policy: default-src 'self'; script-src 'self'; style-src 'self'; img-src 'self' data:; object-src 'none'; base-uri 'self'; frame-ancestors 'none';");

    require_once 'verifica_login.php';

    session_start();
    $_SESSION = [];
    header("Location: login.php");
    session_destroy();
    exit;
?>