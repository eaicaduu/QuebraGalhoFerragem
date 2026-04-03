<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once __DIR__ . '/database.php';
require_once __DIR__ . '/migrations.php';
require_once __DIR__ . '../../models/geral/buscar.php';

$user = null;
$isAdmin = false;

if (
    !isset($_SESSION['user_logged_in'], $_SESSION['user_id']) ||
    $_SESSION['user_logged_in'] !== true
) {
    return;
}

/* if (
    isset($_SESSION['user_agent']) &&
    $_SESSION['user_agent'] !== ($_SERVER['HTTP_USER_AGENT'] ?? '')
) {
    session_unset();
    session_destroy();
    return;
} */

if (!isset($_SESSION['last_regeneration'])) {
    $_SESSION['last_regeneration'] = time();
}

if (time() - $_SESSION['last_regeneration'] > 900) {
    session_regenerate_id(true);
    $_SESSION['last_regeneration'] = time();
}

$user = buscar('usuarios',(int) $_SESSION['user_id'],['id', 'nome', 'email', 'telefone', 'tipo_usuario']);

if (!$user) {
    session_unset();
    session_destroy();
    $user = null;
    $isAdmin = false;
    return;
}

$isAdmin = (int) ($user['tipo_usuario'] ?? 0) >= 2;