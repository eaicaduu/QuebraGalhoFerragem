<?php foreach ($menuItems as $item): ?>

    <?php
    $hasChildren = !empty($item['children']);
    $menuPrefix = $menuPrefix ?? 'desktop';
    $collapseId = $menuPrefix . '_menu_' . ($item['page'] ?? uniqid());
    $isParentActive = false;

    if ($hasChildren) {
        foreach ($item['children'] as $child) {
            $childPage = $child['page'] ?? '';
            $childAcao = $child['acao'] ?? '';

            if ($paginaAtual === $childPage && $acao === $childAcao) {
                $isParentActive = true;
                break;
            }
        }
    } else {
        $isParentActive = ($paginaAtual === ($item['page'] ?? ''));
    }
    ?>

    <li class="nav-item mb-1">

        <?php if ($hasChildren): ?>

            <a class="nav-link d-flex justify-content-between align-items-center <?= $isParentActive ? 'text-warning' : 'menu-link text-white' ?>"
                data-bs-toggle="collapse" href="#<?= $collapseId ?>" role="button"
                aria-expanded="<?= $isParentActive ? 'true' : 'false' ?>" aria-controls="<?= $collapseId ?>">

                <span>
                    <i class="fa <?= $item['icon'] ?> me-2"></i><?= $item['label'] ?>
                </span>

                <i class="fa fa-chevron-down small submenu-arrow <?= $isParentActive ? 'rotated' : '' ?>"></i>
            </a>

            <div class="collapse submenu-collapse <?= $isParentActive ? 'show' : '' ?>" id="<?= $collapseId ?>">
                <div class="ms-4 mt-1 d-flex flex-column gap-1">

                    <?php foreach ($item['children'] as $child): ?>

                        <?php
                        $childActive =
                            $paginaAtual === ($child['page'] ?? '') &&
                            $acao === ($child['acao'] ?? '');
                        ?>

                        <a class="nav-link py-1 small menu-link <?= $childActive ? 'text-warning' : 'text-white-50' ?>"
                            href="admin.php?page=<?= $child['page'] ?><?= !empty($child['acao']) ? '&acao=' . urlencode($child['acao']) : '' ?>">
                            <?= $child['label'] ?>
                        </a>

                    <?php endforeach; ?>

                </div>
            </div>

        <?php else: ?>

            <a class="nav-link <?= $isParentActive ? 'text-warning' : 'menu-link text-white' ?>"
                href="admin.php?page=<?= $item['page'] ?>">
                <i class="fa <?= $item['icon'] ?> me-2"></i><?= $item['label'] ?>
            </a>

        <?php endif; ?>

    </li>

<?php endforeach; ?>