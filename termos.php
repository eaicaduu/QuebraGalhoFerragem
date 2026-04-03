<?php
session_start();
$paginaAtual = basename($_SERVER['PHP_SELF']);

require_once __DIR__ . '/app/config/auth.php';

?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <?php include 'includes/geral/header.php'; ?>
</head>

<body class="d-flex flex-column min-vh-100 user-select-none">

    <!-- NavBar -->
    <?php include 'includes/geral/navbar.php'; ?>

    <main class="flex-fill">
        <div class="container my-2">
            <div class="row">

                <div class="col-12">
                    <?php include 'includes/perfil/termos.php'; ?>
                </div>

            </div>
        </div>
    </main>

    <!-- Rodapé -->
    <?php include 'includes/geral/footer.php'; ?>

</body>