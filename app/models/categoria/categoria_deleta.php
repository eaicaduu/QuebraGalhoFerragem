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

    if (!$pdo) {
        throw new Exception('Falha na conexão com o banco de dados.');
    }

    $stmt = $pdo->prepare("SELECT id, nome FROM categorias WHERE id = :id LIMIT 1");
    $stmt->execute([':id' => $id]);

    $categoria = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$categoria) {
        throw new Exception('Categoria não encontrada.');
    }

    $stmt = $pdo->prepare("DELETE FROM categorias WHERE id = :id");
    $stmt->execute([':id' => $id]);

    echo json_encode([
        'status' => true,
        'mensagem' => 'Categoria excluída com sucesso.'
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