<?php
session_start();

$paginaAtual = $_GET['page'] ?? 'painel';
$acao = $_GET['acao'] ?? 'geral';

require_once __DIR__ . '/app/config/database.php';
require_once __DIR__ . '/app/config/migrations.php';
require_once __DIR__ . '/app/config/auth.php';

require_once __DIR__ . '/app/models/carousel/carousel_listar.php';

if (!$user || !$isAdmin) {
    header('Location: index.php');
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
    <script src="js/admin/carousel/carousel_salvar.js"></script>
    <script src="js/admin/carousel/carousel_editar.js"></script>
    <script src="js/admin/carousel/carousel_deleta.js"></script>
</head>

<body class="d-flex flex-column min-vh-100 user-select-none">

    <!-- NavBar -->
    <?php include 'includes/index/navbar.php'; ?>

    <main class="flex-fill">
        <div class="container my-2">
            <!-- SlideBar -->
            <?php include 'includes/admin/layout/sidebar.php'; ?>
        </div>
    </main>

    <!-- Rodapé -->
    <?php include 'includes/geral/footer.php'; ?>

</body>