<?php
session_start();
$paginaAtual = basename($_SERVER['PHP_SELF']);

require_once __DIR__ . '/app/config/database.php';
require_once __DIR__ . '/app/config/migrations.php'; 
require_once __DIR__ . '/app/config/auth.php';

?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <?php include 'includes/geral/header.php'; ?>

    <!-- JS -->
    <script src="js/perfil/logout.js"></script>
</head>

<body class="d-flex flex-column min-vh-100 user-select-none">

    <!-- NavBar -->
    <?php include 'includes/index/navbar.php'; ?>

    <main class="flex-fill">
        <div class="container my-2">
        </div>
    </main>

    <!-- Rodapé -->
    <?php include 'includes/geral/footer.php'; ?>

</body>