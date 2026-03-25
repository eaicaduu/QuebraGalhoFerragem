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
    <script src="js/geral/pesquisar.js"></script>
    <script src="js/perfil/logout.js"></script>
</head>

<body class="d-flex flex-column min-vh-100 user-select-none">

    <!-- NavBar -->
    <?php include 'includes/geral/navbar.php'; ?>

    <main class="flex-fill">
        <div class="container my-2">
            <!-- Barra Pesquisa -->
            <?php include 'includes/geral/pesquisar.php'; ?>
        </div>
    </main>

    <!-- Rodapé -->
    <?php include 'includes/geral/footer.php'; ?>

</body>