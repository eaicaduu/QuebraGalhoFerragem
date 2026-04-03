<?php
header('Content-Type: application/json');

require_once __DIR__ . '../../../config/database.php';

try {
    $input = json_decode(file_get_contents('php://input'), true);

    $id = isset($input['id']) ? (int) $input['id'] : 0;

    if ($id <= 0) {
        throw new Exception('ID inválido.');
    }

    $db = new Database();
    $pdo = $db->getConnection();

    $stmt = $pdo->prepare("SELECT imagem FROM carousel WHERE id = :id LIMIT 1");
    $stmt->execute([':id' => $id]);

    $carousel = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$carousel) {
        throw new Exception('Imagem não encontrada.');
    }

    if (!empty($carousel['imagem'])) {
        $caminhoImagem = __DIR__ . '/../../' . ltrim($carousel['imagem'], '/');

        if (file_exists($caminhoImagem) && is_file($caminhoImagem)) {
            unlink($caminhoImagem);
        }
    }

    $stmt = $pdo->prepare("DELETE FROM carousel WHERE id = :id");
    $stmt->execute([':id' => $id]);

    echo json_encode([
        'status' => true,
        'mensagem' => 'Imagem excluída com sucesso.'
    ]);
    exit;

} catch (Throwable $e) {
    http_response_code(400);

    echo json_encode([
        'status' => false,
        'mensagem' => $e->getMessage()
    ]);
    exit;
}