<?php
header('Content-Type: application/json');

require_once __DIR__ . '../../../config/database.php';

try {

    $input = json_decode(file_get_contents('php://input'), true);

    $nome = trim($input['nome'] ?? '');
    $preco = (float) ($input['preco'] ?? 0);
    $codigo = trim($input['codigo'] ?? '');

    if ($nome === '') {
        throw new Exception('Nome inválido');
    }

    if ($codigo === '') {
        throw new Exception('Código inválido');
    }

    $db = new Database();
    $pdo = $db->getConnection();

    $stmt = $pdo->prepare("SELECT id FROM produtos WHERE codigo = :codigo LIMIT 1");
    $stmt->execute([':codigo' => $codigo]);

    if ($stmt->fetch()) {
        throw new Exception('Já existe um produto com esse código.');
    }

    $stmt = $pdo->prepare("
        INSERT INTO produtos (
            nome,
            codigo,
            preco,
            estoque,
            ativo,
            criado_em
        ) VALUES (
            :nome,
            :codigo,
            :preco,
            0,
            1,
            NOW()
        )
    ");

    $stmt->execute([
        ':nome' => $nome,
        ':codigo' => $codigo,
        ':preco' => $preco
    ]);

    echo json_encode([
        'status' => true
    ]);

} catch (Throwable $e) {

    http_response_code(400);

    echo json_encode([
        'status' => false,
        'mensagem' => $e->getMessage()
    ]);
}