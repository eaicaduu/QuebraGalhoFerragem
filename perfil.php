<?php
session_start();
$paginaAtual = basename($_SERVER['PHP_SELF']);

require_once __DIR__ . '/app/config/auth.php'; 

?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <?php include 'includes/geral/header.php'; ?>

    <!-- JS -->
    <script src="js/perfil/conta.js"></script>
    <script src="js/perfil/login.js"></script>
    <script src="js/perfil/cadastro.js"></script>
    <script src="js/perfil/logout.js"></script>

    <script src="js/utils/telefone.js"></script>
    <script src="js/utils/maiuscula.js"></script>
</head>

<body class="d-flex flex-column min-vh-100 user-select-none">

    <!-- NavBar -->
    <?php include 'includes/geral/navbar.php'; ?>

    <main class="flex-fill">
        <div class="container my-2">
            <div class="row">

                <?php if ($user) { ?>

                    <!-- Conta -->
                    <div class="col-12 col-lg-4 mb-3 mb-lg-0">
                        <?php include 'includes/perfil/conta.php'; ?>
                    </div>

                    <!-- Pedidos -->
                    <div class="col-12 col-lg-8">
                        <?php include 'includes/perfil/pedidos.php'; ?>
                    </div>

                <?php } else { ?>

                    <div class="col-12">
                        <?php include 'includes/perfil/guest.php'; ?>
                    </div>

                <?php } ?>

            </div>
        </div>
    </main>

    <!-- Rodapé -->
    <?php include 'includes/geral/footer.php'; ?>

</body>