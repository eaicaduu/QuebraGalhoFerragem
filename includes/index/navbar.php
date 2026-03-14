<nav class="navbar navbar-expand-lg">
    <div class="container">

        <!-- Botão menu mobile -->
        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarContent">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Logo mobile central -->
        <a class="navbar-brand d-lg-none pe-none" href="index.php">
            <img src="images/logo.png" height="50">
        </a>

        <!-- Logo desktop -->
        <a class="navbar-brand d-none d-lg-block pe-none" href="index.php">
            <img src="images/logo.png" height="50">
        </a>

        <!-- Menu -->
        <div class="collapse navbar-collapse justify-content-lg-end" id="navbarContent">
            <ul class="navbar-nav d-flex flex-lg-row align-items-lg-center gap-lg-3">

                <li class="nav-item fw-bold">
                    <a class="nav-link d-flex align-items-center <?= ($paginaAtual == 'index.php') ? 'active' : '' ?>"
                        href="index.php">
                        <i class="fa-solid fa-home me-2"></i> Início
                    </a>
                </li>

                <li class="nav-item fw-bold">
                    <a class="nav-link d-flex align-items-center <?= ($paginaAtual == 'pesquisar.php') ? 'active' : '' ?>"
                        href="pesquisar.php">
                        <i class="fa-solid fa-search me-2"></i> Pesquisar
                    </a>
                </li>

                <li class="nav-item fw-bold">
                    <a class="nav-link d-flex align-items-center <?= ($paginaAtual == 'carrinho.php') ? 'active' : '' ?>"
                        href="carrinho.php">
                        <i class="fa-solid fa-cart-shopping me-2"></i> Carrinho
                    </a>
                </li>

                <li class="nav-item fw-bold">
                    <a class="nav-link d-flex align-items-center <?= ($paginaAtual == 'perfil.php') ? 'active' : '' ?>"
                        style="cursor:pointer" href="perfil.php">
                        <i class="fa-solid fa-user me-2"></i>
                        <?= isset($user['nome']) ? htmlspecialchars(explode(' ', trim($user['nome']))[0]) : 'Perfil' ?>
                    </a>
                </li>

                <?php if (isset($user['tipo_usuario']) && in_array($user['tipo_usuario'], [2, 3])): ?>
                    <li class="nav-item fw-bold">
                        <a href="admin.php" style="cursor:pointer;" class="nav-link d-flex align-items-center">
                            <i class="fa-solid fa-tools me-1"></i> Painel
                        </a>
                    </li>
                <?php endif; ?>

                <?php if (isset($user['nome'])): ?>
                    <li class="nav-item fw-bold">
                        <a onclick="abrirLogoutSwal()" style="cursor:pointer;" class="nav-link d-flex align-items-center">
                            <i class="fa-solid fa-right-from-bracket me-1"></i> Sair
                        </a>
                    </li>
                <?php endif; ?>

            </ul>
        </div>

    </div>
</nav>