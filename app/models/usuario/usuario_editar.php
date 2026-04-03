<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '../../../config/database.php';
require_once __DIR__ . '../../../config/migrations.php';

try {
    $db = new Database();
    $pdo = $db->getConnection();

    if (!$pdo) {
        throw new Exception('Erro na conexão com o banco.');
    }

    runMigrations($pdo);

    $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
    $tipoUsuario = isset($_POST['tipo_usuario']) ? (int) $_POST['tipo_usuario'] : 1;

    if ($id <= 0) {
        throw new Exception('ID do usuário inválido.');
    }

    if (!in_array($tipoUsuario, [1, 2], true)) {
        throw new Exception('Tipo de usuário inválido.');
    }

    $stmt = $pdo->prepare("
        UPDATE usuarios
        SET tipo_usuario = :tipo_usuario,
            atualizado_em = NOW()
        WHERE id = :id
    ");

    $stmt->execute([
        ':id' => $id,
        ':tipo_usuario' => $tipoUsuario
    ]);

    echo json_encode([
        'status' => true,
        'mensagem' => 'Tipo de usuário atualizado com sucesso.'
    ], JSON_UNESCAPED_UNICODE);
    exit;

} catch (Throwable $e) {
    http_response_code(400);

    echo json_encode([
        'status' => false,
        'mensagem' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
    exit;
}