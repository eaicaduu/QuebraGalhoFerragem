<?php
$menuItems = [

    [
        'page' => 'dashboard',
        'icon' => 'fa-home',
        'label' => 'Painel',
        'file' => 'includes/admin/dashboard.php'
    ],

    [
        'page' => 'produtos',
        'icon' => 'fa-tag',
        'label' => 'Produtos',
        'children' => [

            [
                'page' => 'produtos',
                'label' => 'Todos produtos',
                'file' => 'includes/admin/produto_todos.php'
            ],

            [
                'page' => 'produtos',
                'acao' => 'novo',
                'label' => 'Novo produto',
                'file' => 'includes/admin/produto_novo.php'
            ],

            [
                'page' => 'categorias',
                'label' => 'Categorias',
                'file' => 'includes/admin/categorias.php'
            ],
        ]
    ],

    [
        'page' => 'pedidos',
        'icon' => 'fa-box',
        'label' => 'Pedidos',
        'file' => 'includes/admin/pedidos.php'
    ],

    [
        'page' => 'usuarios',
        'icon' => 'fa-users',
        'label' => 'Usuários',
        'file' => 'includes/admin/usuarios.php'
    ],

    [
        'page' => 'configuracoes',
        'icon' => 'fa-gear',
        'label' => 'Configurações',
        'children' => [

            [
                'page' => 'configuracoes',
                'acao' => 'carousel',
                'label' => 'Carousel',
                'file' => 'includes/admin/configuracoes_carousel.php'
            ],

        ]
    ],
];
?>

<ul class="nav flex-column">

    <?php foreach ($menuItems as $item): ?>

        <?php
        $hasChildren = !empty($item['children']);
        $isActive = $paginaAtual === $item['page'];
        $collapseId = 'menu_' . $item['page'];
        ?>

        <li class="nav-item">

            <?php if ($hasChildren): ?>

                <a class="nav-link d-flex justify-content-between align-items-center <?= $isActive ? 'text-warning' : 'menu-link text-white' ?>"
                    data-bs-toggle="collapse" href="#<?= $collapseId ?>" role="button">

                    <span>
                        <i class="fa <?= $item['icon'] ?> me-2"></i><?= $item['label'] ?>
                    </span>

                    <i class="fa fa-chevron-down small"></i>

                </a>

                <div class="collapse <?= $isActive ? 'show' : '' ?>" id="<?= $collapseId ?>">

                    <div class="ms-4 mt-1 d-flex flex-column">

                        <?php foreach ($item['children'] as $child): ?>

                            <?php
                            $childActive =
                                $paginaAtual === $child['page'] &&
                                ($acao ?? '') === ($child['acao'] ?? '');
                            ?>

                            <a class="nav-link py-1 small <?= $childActive ? 'text-warning fw-bold' : 'text-white-50' ?>"
                                href="admin.php?page=<?= $child['page'] ?><?= !empty($child['acao']) ? '&acao=' . $child['acao'] : '' ?>">

                                <?= $child['label'] ?>

                            </a>

                        <?php endforeach; ?>

                    </div>

                </div>

            <?php else: ?>

                <a class="nav-link <?= $isActive ? 'text-warning' : 'menu-link text-white' ?>"
                    href="admin.php?page=<?= $item['page'] ?>">

                    <i class="fa <?= $item['icon'] ?> me-2"></i><?= $item['label'] ?>

                </a>

            <?php endif; ?>

        </li>

    <?php endforeach; ?>

</ul>