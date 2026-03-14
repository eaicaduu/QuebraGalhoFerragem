<div class="d-md-none position-relative my-2 text-end">
    <button class="btn btn-dark d-flex align-items-center ms-auto" type="button" data-bs-toggle="collapse"
        data-bs-target="#menuMobile" aria-controls="menuMobile">
        Menu
        <i id="iconMenuMobile" class="fa fa-caret-left ms-2 rotate-icon"></i>
    </button>

    <div class="collapse position-absolute bg-dark end-0 mt-1 rounded shadow-sm" id="menuMobile"
        style="top:100%; min-width:200px; z-index:1050;">
        <ul class="nav flex-column p-2">
            <?php include 'pagesbar.php'; ?>
        </ul>
    </div>
</div>

<div class="row">

    <nav class="col-md-3 col-lg-2 p-3 d-none rounded d-md-block bg-dark">
        <ul class="nav flex-column">
            <?php include 'pagesbar.php'; ?>
        </ul>
    </nav>

    <main class="col-md-9 col-lg-10 ms-sm-auto px-md-4 mt-3 mt-md-0">

        <?php

        switch ($paginaAtual) {

            case 'produtos.php':
                include 'includes/admin/produtos.php';
                break;

            case 'pedidos.php':
                include 'includes/admin/pedidos.php';
                break;

            case 'usuarios.php':
                include 'includes/admin/usuarios.php';
                break;

            case 'configuracoes.php':
                include 'includes/admin/configuracoes.php';
                break;

            case 'admin.php':
                include 'includes/admin/dashboard.php';
                break;
        }

        ?>

    </main>

</div>