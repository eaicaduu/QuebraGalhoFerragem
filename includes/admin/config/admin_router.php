<?php

function findAdminPageFile(array $menuItems, string $paginaAtual, string $acao = ''): string
{
    foreach ($menuItems as $item) {
        if (!empty($item['children'])) {
            foreach ($item['children'] as $child) {
                $childPage = $child['page'] ?? '';
                $childAcao = $child['acao'] ?? '';

                if ($paginaAtual === $childPage && $acao === $childAcao) {
                    return $child['file'];
                }
            }
        }

        $itemPage = $item['page'] ?? '';

        if ($paginaAtual === $itemPage && isset($item['file'])) {
            return $item['file'];
        }
    }

    return 'includes/admin/pages/painel_geral.php';
}