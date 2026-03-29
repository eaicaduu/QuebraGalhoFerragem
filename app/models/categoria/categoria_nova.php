<?php
header('Content-Type: application/json');

require_once __DIR__ . '../../../config/database.php';

try {
    $db = new Database();
    $pdo = $db->getConnection();

    if (!$pdo) {
        throw new Exception('Falha na conexão com o banco de dados.');
    }

    $nome = isset($_POST['nome']) ? trim((string) $_POST['nome']) : '';
    $ativo = isset($_POST['ativo']) ? (int) $_POST['ativo'] : 1;

    if ($nome === '') {
        http_response_code(400);
        echo json_encode([
            'status' => false,
            'mensagem' => 'Informe o nome da categoria.'
        ]);
        exit;
    }

    if (!in_array($ativo, [0, 1], true)) {
        $ativo = 1;
    }

    $stmtVerifica = $pdo->prepare("
        SELECT id
        FROM categorias
        WHERE nome = :nome
        LIMIT 1
    ");
    $stmtVerifica->execute([
        ':nome' => $nome
    ]);

    if ($stmtVerifica->fetch(PDO::FETCH_ASSOC)) {
        http_response_code(400);
        echo json_encode([
            'status' => false,
            'mensagem' => 'Já existe uma categoria com esse nome.'
        ]);
        exit;
    }

    $stmt = $pdo->prepare("
        INSERT INTO categorias (
            nome,
            ativo
        ) VALUES (
            :nome,
            :ativo
        )
    ");

    $stmt->execute([
        ':nome' => $nome,
        ':ativo' => $ativo
    ]);

    echo json_encode([
        'status' => true,
        'mensagem' => 'Categoria salva com sucesso.',
        'id' => $pdo->lastInsertId()
    ]);
    exit;

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'status' => false,
        'mensagem' => 'Erro interno: ' . $e->getMessage()
    ]);
    exit;
}