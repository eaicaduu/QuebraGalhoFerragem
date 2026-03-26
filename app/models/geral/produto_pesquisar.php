<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../config/migrations.php';
require_once __DIR__ . '/../geral/produto_listar.php';

try {
    $pesquisa = $_GET['pesquisa'] ?? '';
    $contexto = $_GET['contexto'] ?? 'usuario';

    $produtos = listarProdutos($pesquisa, $contexto);

    ob_start();

    if ($contexto === 'admin') {
        include __DIR__ . '/../../../includes/admin/pages/produto_resultado.php';
    } else {
        include __DIR__ . '/../../../includes/geral/produtos.php';
    }

    $html = ob_get_clean();

    echo json_encode([
        'status' => true,
        'html' => $html
    ], JSON_UNESCAPED_UNICODE);
    exit;

} catch (Throwable $e) {
    http_response_code(500);

    echo json_encode([
        'status' => false,
        'mensagem' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
    exit;
}