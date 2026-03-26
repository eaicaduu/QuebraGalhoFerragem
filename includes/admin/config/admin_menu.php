<?php
$menuItems = [
    [
        'page' => 'painel',
        'icon' => 'fa-home',
        'label' => 'Painel',
        'file' => 'includes/admin/pages/painel_geral.php',
        'children' => [
            [
                'page' => 'painel',
                'acao' => 'geral',
                'label' => 'Painel Geral',
                'file' => 'includes/admin/pages/painel_geral.php',
            ],
        ],
    ],

    [
        'page' => 'produtos',
        'icon' => 'fa-tag',
        'label' => 'Produtos',
        'children' => [
            [
                'page' => 'produtos',
                'acao' => 'todos',
                'label' => 'Todos produtos',
                'file' => 'includes/admin/pages/produto_todos.php',
            ],
            [
                'page' => 'produtos',
                'acao' => 'novo',
                'label' => 'Novo produto',
                'file' => 'includes/admin/pages/produto_form.php',
            ],
            [
                'page' => 'produtos',
                'acao' => 'editar',
                'label' => 'Editar produto',
                'file' => 'includes/admin/pages/produto_form.php',
                'visible' => function () {
                    return isset($_GET['acao'], $_GET['id']) &&
                        $_GET['acao'] === 'editar' &&
                        (int) $_GET['id'] > 0;
                }
            ],
            [
                'page' => 'categorias',
                'acao' => 'categoria',
                'label' => 'Categorias',
                'file' => 'includes/admin/pages/categorias.php',
            ],
            [
                'page' => 'importar',
                'acao' => 'importar',
                'label' => 'Importar',
                'file' => 'includes/admin/pages/produto_importar.php',
            ],
            [
                'page' => 'importar',
                'acao' => 'visualizar',
                'label' => 'Visualizar',
                'file' => 'includes/admin/pages/produto_vizualizar.php',
                'visible' => function () {
                    return !empty($_SESSION['import_rows']) && !empty($_SESSION['import_headers']);
                }
            ],
        ],
    ],

    [
        'page' => 'pedidos',
        'icon' => 'fa-box',
        'label' => 'Pedidos',
        'children' => [
            [
                'page' => 'pedidos',
                'acao' => 'Todos pedidos',
                'label' => 'Todos Pedidos',
                'file' => 'includes/admin/pages/pedidos_todos.php',
            ],
        ],
    ],

    [
        'page' => 'usuarios',
        'icon' => 'fa-users',
        'label' => 'Usuários',
        'children' => [
            [
                'page' => 'usuarios',
                'acao' => 'Todos usuarios',
                'label' => 'Todos usuários',
                'file' => 'includes/admin/pages/usuarios_todos.php',
            ],
        ],
    ],

    [
        'page' => 'configuracoes',
        'icon' => 'fa-gear',
        'label' => 'Configurações',
        'children' => [
            [
                'page' => 'configuracoes',
                'acao' => 'carousel',
                'label' => 'Imagens Carousel',
                'file' => 'includes/admin/pages/configuracoes_carousel.php',
            ],
            [
                'page' => 'configuracoes',
                'acao' => 'editar',
                'label' => 'Editar Imagem',
                'file' => 'includes/admin/pages/configuracoes_carousel.php',
                'visible' => function () {
                    return isset($_GET['acao'], $_GET['id']) &&
                        $_GET['acao'] === 'editar' &&
                        (int) $_GET['id'] > 0;
                }
            ],
        ],
    ],
];