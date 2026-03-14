<?php
session_start();
$paginaAtual = basename($_SERVER['PHP_SELF']);

require_once __DIR__ . '/app/config/auth.php';

if (!$user || !$isAdmin) {
    header("Location: index.php");
    exit;
}

?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <?php include 'includes/geral/header.php'; ?>

    <!-- JS -->
    <script src="js/perfil/logout.js"></script>
    <script src="js/admin/slidebar.js"></script>
</head>

<body class="d-flex flex-column min-vh-100 user-select-none">

    <!-- NavBar -->
    <?php include 'includes/index/navbar.php'; ?>

    <main class="flex-fill">
        <div class="container my-2">
            <!-- SlideBar -->
            <?php include 'includes/admin/slidebar.php'; ?>
        </div>
    </main>

    <!-- Rodapé -->
    <?php include 'includes/geral/footer.php'; ?>

</body>