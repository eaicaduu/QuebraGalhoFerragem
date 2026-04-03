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

    <!-- CSS -->
    <link rel="stylesheet" href="css/carousel.css">

    <!-- JS -->
    <script src="js/index/carousel.js"></script>
    <script src="js/geral/pesquisar.js"></script>
    <script src="js/perfil/login.js"></script>
    <script src="js/perfil/cadastro.js"></script>
    <script src="js/perfil/logout.js"></script>
</head>

<body class="d-flex flex-column min-vh-100 user-select-none">

    <!-- NavBar -->
    <?php include 'includes/geral/navbar.php'; ?>

    <!-- Cards -->
    <?php include 'includes/index/carousel.php'; ?>

    <main class="flex-fill">
        <div class="container my-2">

            <!-- Produtos -->
            <div id="resultadoProdutos">
                <?php include 'includes/geral/produtos.php'; ?>
            </div>

            <!-- Sobre -->
            <?php /* include 'includes/index/sobre.php'; */ ?>

            <!-- Onde estamos -->
            <?php /* include 'includes/index/localizacao.php'; */ ?>
        </div>
    </main>

    <!-- Botão Whatsapp -->
    <?php include 'includes/index/whatsapp.php'; ?>

    <!-- Rodapé -->
    <?php include 'includes/geral/footer.php'; ?>

</body>

</html>