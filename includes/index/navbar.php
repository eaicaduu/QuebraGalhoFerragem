<nav class="navbar navbar-expand-lg">
    <div class="container">
        <button class="navbar-toggler invisible" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"
            aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <!-- Logo -->
        <a class="navbar-brand mx-auto d-lg-none pe-none" href="#">
            <img src="images/logo.png" height="50" class="pe-none" />
        </a>

        <!-- Botão Abrir -->
        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false"
            aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Menu completo -->
        <div class="collapse navbar-collapse justify-content-between align-items-center" id="navbarContent">

            <!-- Menu à esquerda -->
            <ul class="navbar-nav d-flex flex-lg-row mb-lg-0 align-items-lg-center">
                <li class="nav-item fw-bold">
                    <a style="cursor:pointer" class="nav-link d-flex align-items-center" <?= isset($_SESSION['sessao_ativa'])
                        ? 'href="perfil.php"'
                        : 'onclick="abrirLoginSwal()"' ?>>
                        <i class="fa-solid fa-user me-2"></i> Perfil
                    </a>
                </li>

                <li style="cursor:pointer" class="nav-item fw-bold">
                    <a class="nav-link d-flex align-items-center" href="#sobre">
                        <i class="fa-solid fa-search me-2"></i> Pesquisar
                    </a>
                </li>
            </ul>

            <!-- Logo central -->
            <a class="navbar-brand d-none d-lg-block" href="#">
                <img src="images/logo.png" height="50" class="pe-none">
            </a>

            <!-- Menu à direita -->
            <ul class="navbar-nav d-flex flex-lg-row mb-2 mb-lg-0 align-items-lg-center">
                <li style="cursor:pointer" class="nav-item fw-bold">
                    <a class="nav-link d-flex align-items-center" href="carrinho.php">
                        <i class="fa-solid fa-cart-shopping me-2"></i> Carrinho
                    </a>
                </li>

                <li style="cursor:pointer" class="nav-item fw-bold">
                    <a class="nav-link d-flex align-items-center" href="#footer">
                        <i class="fa-solid fa-phone me-2"></i> Contato
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>