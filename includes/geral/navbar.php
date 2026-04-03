<nav class="navbar navbar-expand-lg position-sticky top-0 bg-white z-3">
    <div class="container">

        <!-- Linha de cima -->
        <div class="d-flex align-items-center justify-content-between flex-grow-1 flex-lg-grow-0">
            <!-- Botão menu mobile -->
            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Logo mobile -->
            <a class="navbar-brand d-lg-none m-0" href="index.php">
                <img src="images/logo.png" height="50">
            </a>

            <!-- Logo desktop -->
            <a class="navbar-brand d-none d-lg-block" href="index.php">
                <img src="images/logo.png" height="50">
            </a>

        </div>

        <!-- Busca mobile -->
        <?php if ($paginaAtual == 'index.php'): ?>
        <div class="w-100 d-lg-none mt-2">
            <div class="position-relative">
                <i class="fa fa-search position-absolute top-50 translate-middle-y text-muted" style="left: 14px;"></i>

                <input type="text" id="inputPesquisarProdutoMobile" class="form-control ps-5" data-contexto="usuario"
                    placeholder="Buscar produtos...">
            </div>
        </div>
        <?php endif; ?>

        <!-- Menu desktop/mobile -->
        <div class="collapse navbar-collapse justify-content-lg-end" id="navbarContent">

            <!-- Busca desktop -->
            <?php if ($paginaAtual == 'index.php'): ?>
            <div class="flex-grow-1 d-flex justify-content-center px-lg-3 my-2 my-lg-0 d-none d-lg-flex">
                <div style="width:100%; max-width:500px;">
                    <div class="position-relative">
                        <i class="fa fa-search position-absolute top-50 translate-middle-y text-muted"
                            style="left: 14px;"></i>

                        <input type="text" id="inputPesquisarProduto" class="form-control ps-5" data-contexto="usuario"
                            placeholder="Buscar produtos...">
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <ul class="navbar-nav d-flex flex-lg-row align-items-lg-center gap-lg-3">

                <li class="nav-item fw-bold">
                    <a class="nav-link d-flex align-items-center <?= ($paginaAtual == 'index.php') ? 'active' : '' ?>"
                        href="index.php" draggable="false">
                        <i class="fa-solid fa-home me-2"></i> Início
                    </a>
                </li>

                <li class="nav-item fw-bold">
                    <a class="nav-link d-flex align-items-center <?= ($paginaAtual == 'perfil.php') ? 'active' : '' ?>"
                        href="perfil.php" draggable="false">
                        <i class="fa-solid fa-user me-2"></i>
                        <?= isset($user['nome']) ? htmlspecialchars(explode(' ', trim($user['nome']))[0]) : 'Minha Conta' ?>
                    </a>
                </li>

                <?php if (isset($user['tipo_usuario']) && $user['tipo_usuario'] >= 2): ?>
                    <li class="nav-item fw-bold">
                        <a href="admin.php" draggable="false"
                            class="nav-link d-flex align-items-center <?= ($arquivoAtual === 'admin.php') ? 'active' : '' ?>">
                            <i class="fa-solid fa-tools me-1"></i> Painel
                        </a>
                    </li>
                <?php endif; ?>

                <?php if (isset($user['nome'])): ?>
                    <li class="nav-item fw-bold">
                        <a onclick="abrirLogoutSwal()" draggable="false" style="cursor:pointer;"
                            class="nav-link d-flex align-items-center">
                            <i class="fa-solid fa-right-from-bracket me-1"></i> Sair
                        </a>
                    </li>
                <?php endif; ?>

            </ul>
        </div>

    </div>
</nav>