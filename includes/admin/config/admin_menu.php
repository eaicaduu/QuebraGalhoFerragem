<?php

$menuItems = [
    [
        'page' => 'dashboard',
        'icon' => 'fa-home',
        'label' => 'Painel',
        'file' => 'includes/admin/pages/dashboard.php',
    ],

    [
        'page' => 'produtos',
        'icon' => 'fa-tag',
        'label' => 'Produtos',
        'children' => [
            [
                'page' => 'produtos',
                'label' => 'Todos produtos',
                'file' => 'includes/admin/pages/produto_todos.php',
            ],
            [
                'page' => 'produtos',
                'acao' => 'novo',
                'label' => 'Novo produto',
                'file' => 'includes/admin/pages/produto_novo.php',
            ],
            [
                'page' => 'categorias',
                'label' => 'Categorias',
                'file' => 'includes/admin/pages/categorias.php',
            ],
            [
                'page' => 'importar',
                'label' => 'Importar',
                'file' => 'includes/admin/pages/produto_importar.php',
            ],
        ],
    ],

    [
        'page' => 'pedidos',
        'icon' => 'fa-box',
        'label' => 'Pedidos',
        'file' => 'includes/admin/pages/pedidos.php',
    ],

    [
        'page' => 'usuarios',
        'icon' => 'fa-users',
        'label' => 'Usuários',
        'file' => 'includes/admin/pages/usuarios.php',
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
                'file' => 'includes/admin/pages/configuracoes_carousel.php',
            ],
        ],
    ],
];