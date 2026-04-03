<?php
require_once __DIR__ . '/../config/admin_menu.php';
require_once __DIR__ . '/../config/admin_router.php';
?>

<div class="d-lg-none position-relative my-2 text-end">
    <button class="btn btn-dark d-flex align-items-center ms-auto" type="button" data-bs-toggle="collapse"
        data-bs-target="#menuMobile" aria-controls="menuMobile" aria-expanded="false">
        Menu
        <i id="iconMenuMobile" class="fa fa-caret-left ms-2 rotate-icon"></i>
    </button>

    <div class="collapse position-absolute bg-dark end-0 mt-1 rounded shadow-sm" id="menuMobile"
        style="top:100%; min-width:220px; z-index:1050;">
        <ul class="nav flex-column p-2">
            <?php $menuPrefix = 'mobile'; ?>
            <?php include __DIR__ . '/menu.php'; ?>
        </ul>
    </div>
</div>

<div class="row">

    <nav class="col-lg-3 p-3 d-none h-100 d-lg-block bg-dark rounded">
        <ul class="nav flex-column">
            <?php $menuPrefix = 'desktop'; ?>
            <?php include __DIR__ . '/menu.php'; ?>
        </ul>
    </nav>

    <main class="col-lg-9 px-lg-4 mt-3 mt-lg-0">
        <?php
        $pageFile = findAdminPageFile($menuItems, $paginaAtual, $acao);

        if (file_exists($pageFile)) {
            include $pageFile;
        } else {
            include 'includes/admin/pages/painel_geral.php';
        }
        ?>
    </main>

</div>