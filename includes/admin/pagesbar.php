<?php
$menuItems = [
    ['href' => 'admin.php',          'icon' => 'fa-home',  'label' => 'Painel'],
    ['href' => 'produtos.php',       'icon' => 'fa-tag',   'label' => 'Produtos'],
    ['href' => 'pedidos.php',        'icon' => 'fa-box',   'label' => 'Pedidos'],
    ['href' => 'usuarios.php',       'icon' => 'fa-users', 'label' => 'Usuários'],
    ['href' => 'configuracoes.php',  'icon' => 'fa-gear',  'label' => 'Configurações'],
];

foreach ($menuItems as $item): ?>
    <li class="nav-item">
        <a class="nav-link <?= ($paginaAtual == $item['href']) ? 'text-warning' : 'menu-link text-white' ?>"
           href="<?= $item['href'] ?>">
            <i class="fa <?= $item['icon'] ?> me-2"></i><?= $item['label'] ?>
        </a>
    </li>
<?php endforeach; ?>