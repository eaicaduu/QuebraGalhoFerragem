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
        'page' => 'produto',
        'icon' => 'fa-tag',
        'label' => 'Produtos',
        'children' => [
            [
                'page' => 'produto',
                'acao' => 'todos produtos',
                'label' => 'Produtos',
                'file' => 'includes/admin/pages/produto_todos.php',
            ],
            [
                'page' => 'produto',
                'acao' => 'novo produto',
                'label' => 'Novo Produto',
                'file' => 'includes/admin/pages/produto_form.php',
                'visible' => function () {
                    return isset($_GET['acao']) &&
                        $_GET['acao'] === 'novo produto';
                }
            ],
            [
                'page' => 'produto',
                'acao' => 'editar produto',
                'label' => 'Editar Produto',
                'file' => 'includes/admin/pages/produto_form.php',
                'visible' => function () {
                    return isset($_GET['acao'], $_GET['id']) &&
                        $_GET['acao'] === 'editar produto' &&
                        (int) $_GET['id'] > 0;
                }
            ],
            [
                'page' => 'produto',
                'acao' => 'firebird',
                'label' => 'Firebird',
                'file' => 'includes/admin/pages/produto_firebird.php',
                'visible' => function () {
                    return isset($_GET['acao']) &&
                        $_GET['acao'] === 'firebird';
                }
            ],
            [
                'page' => 'categoria',
                'acao' => 'todas categorias',
                'label' => 'Categorias',
                'file' => 'includes/admin/pages/categoria_todas.php',
            ],
            [
                'page' => 'categoria',
                'acao' => 'nova categoria',
                'label' => 'Nova Categoria',
                'file' => 'includes/admin/pages/categoria_form.php',
                'visible' => function () {
                    return isset($_GET['acao']) &&
                        $_GET['acao'] === 'nova categoria';
                }
            ],
            [
                'page' => 'categoria',
                'acao' => 'editar categoria',
                'label' => 'Editar Categoria',
                'file' => 'includes/admin/pages/categoria_form.php',
                'visible' => function () {
                    return isset($_GET['acao'], $_GET['id']) &&
                        $_GET['acao'] === 'editar categoria' &&
                        (int) $_GET['id'] > 0;
                }
            ],
        ],
    ],

    [
        'page' => 'usuario',
        'icon' => 'fa-users',
        'label' => 'Usuários',
        'children' => [
            [
                'page' => 'usuario',
                'acao' => 'todos usuarios',
                'label' => 'Todos usuários',
                'file' => 'includes/admin/pages/usuario_todos.php',
            ],
        ],
    ],

    [
        'page' => 'configuracao',
        'icon' => 'fa-gear',
        'label' => 'Configurações',
        'children' => [
            [
                'page' => 'configuracao',
                'acao' => 'imagens carousel',
                'label' => 'Imagens Carousel',
                'file' => 'includes/admin/pages/configuracao_carousel.php',
            ],
            [
                'page' => 'configuracao',
                'acao' => 'editar',
                'label' => 'Editar Imagem',
                'file' => 'includes/admin/pages/configuracao_carousel.php',
                'visible' => function () {
                    return isset($_GET['acao'], $_GET['id']) &&
                        $_GET['acao'] === 'editar' &&
                        (int) $_GET['id'] > 0;
                }
            ],
        ],
    ],
];
