<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '../../../config/database.php';

try {
    $db = new Database();
    $pdo = $db->getConnection();

    if (!$pdo) {
        throw new Exception('Falha na conexão com o banco de dados.');
    }

    $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
    $nome = isset($_POST['nome']) ? trim((string) $_POST['nome']) : '';
    $ativo = isset($_POST['ativo']) ? (int) $_POST['ativo'] : 1;

    if ($id <= 0) {
        http_response_code(400);
        echo json_encode([
            'status' => false,
            'mensagem' => 'ID da categoria inválido.'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    if ($nome === '') {
        http_response_code(400);
        echo json_encode([
            'status' => false,
            'mensagem' => 'Informe o nome da categoria.'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    if (!in_array($ativo, [0, 1], true)) {
        $ativo = 1;
    }

    $stmtCategoria = $pdo->prepare("
        SELECT id
        FROM categorias
        WHERE id = :id
        LIMIT 1
    ");
    $stmtCategoria->execute([
        ':id' => $id
    ]);

    if (!$stmtCategoria->fetch(PDO::FETCH_ASSOC)) {
        http_response_code(404);
        echo json_encode([
            'status' => false,
            'mensagem' => 'Categoria não encontrada.'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $stmtVerifica = $pdo->prepare("
        SELECT id
        FROM categorias
        WHERE nome = :nome
          AND id <> :id
        LIMIT 1
    ");
    $stmtVerifica->execute([
        ':nome' => $nome,
        ':id' => $id
    ]);

    if ($stmtVerifica->fetch(PDO::FETCH_ASSOC)) {
        http_response_code(400);
        echo json_encode([
            'status' => false,
            'mensagem' => 'Já existe outra categoria com esse nome.'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    $stmt = $pdo->prepare("
        UPDATE categorias
        SET nome = :nome,
            ativo = :ativo
        WHERE id = :id
    ");

    $stmt->execute([
        ':nome' => $nome,
        ':ativo' => $ativo,
        ':id' => $id
    ]);

    echo json_encode([
        'status' => true,
        'mensagem' => 'Categoria atualizada com sucesso.'
    ], JSON_UNESCAPED_UNICODE);
    exit;

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'status' => false,
        'mensagem' => 'Erro interno: ' . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
    exit;
}