<?php
session_start();

$paginaAtual = $_GET['page'] ?? 'painel';
$acao = $_GET['acao'] ?? 'geral';

require_once __DIR__ . '/app/config/database.php';
require_once __DIR__ . '/app/config/migrations.php';
require_once __DIR__ . '/app/config/auth.php';

if (!$user || !$isAdmin) {
    header('Location: index.php');
    exit;
}

?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <?php include 'includes/geral/header.php'; ?>

    <!-- CSS -->
    <link rel="stylesheet" href="css/carousel.css">

    <!-- JS -->
    <script src="js/perfil/logout.js"></script>
    <script src="js/geral/sidebar.js"></script>
    <script src="js/admin/carousel/carousel_salvar.js"></script>
    <script src="js/admin/carousel/carousel_deleta.js"></script>
    <script src="js/admin/carousel/carousel_select.js"></script>
    <script src="js/admin/importar/importar_upload.js"></script>
    <script src="js/admin/importar/importar_salvar.js"></script>
    <script src="js/admin/importar/importar_cancel.js"></script>
    <script src="js/admin/produto/produto_novo.js"></script>
    <script src="js/admin/produto/produto_editar.js"></script>
    <script src="js/admin/produto/produto_deleta.js"></script>
</head>

<body class="d-flex flex-column min-vh-100 user-select-none">

    <!-- NavBar -->
    <?php include 'includes/geral/navbar.php'; ?>

    <main class="flex-fill">
        <div class="container my-2">
            <!-- SlideBar -->
            <?php include 'includes/admin/layout/sidebar.php'; ?>
        </div>
    </main>

    <!-- Rodapé -->
    <?php include 'includes/geral/footer.php'; ?>

</body>