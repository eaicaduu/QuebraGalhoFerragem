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
                'file' => 'includes/admin/pages/produto_novo.php',
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
                'label' => 'Carousel',
                'file' => 'includes/admin/pages/configuracoes_carousel.php',
            ],
        ],
    ],
];