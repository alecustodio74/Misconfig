<?php
    header("Content-Security-Policy: default-src 'self'; script-src 'self'; style-src 'self'; img-src 'self' data:; object-src 'none'; base-uri 'self'; frame-ancestors 'none';");
    
    require_once 'verifica_login.php';

    $host = getenv('DB_HOST');
    $db   = getenv('DB_NAME');
    $user = getenv('DB_USER');
    $pass = getenv('DB_PASS');
    
//require_once("conexao.php");
//$host = 'localhost';
//$db = 'backup';
//$user = 'root';
//$pass = '';
?>