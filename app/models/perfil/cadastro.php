<?php
require_once __DIR__ . '../../../config/database.php';
require_once __DIR__ . '../../../config/migrations.php';

header('Content-Type: application/json; charset=utf-8');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode([
            'success' => false,
            'message' => 'Método não permitido'
        ]);
        exit;
    }

    $rawInput = file_get_contents('php://input');
    $input = json_decode($rawInput, true);

    if (!is_array($input)) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Dados inválidos'
        ]);
        exit;
    }

    $nome = trim((string) ($input['nome'] ?? ''));
    $email = mb_strtolower(trim((string) ($input['email'] ?? '')));
    $senha = (string) ($input['senha'] ?? '');

    if ($nome === '' || $email === '' || $senha === '') {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Por favor, preencha todos os campos'
        ]);
        exit;
    }

    if (mb_strlen($nome) < 3 || mb_strlen($nome) > 120) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Informe um nome válido'
        ]);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email) > 150) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'Email inválido'
        ]);
        exit;
    }

    if (strlen($senha) < 8) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'message' => 'A senha precisa ter no mínimo 8 caracteres'
        ]);
        exit;
    }

    $db = new Database();
    $conn = $db->getConnection();

    if (!$conn) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Erro ao processar a solicitação'
        ]);
        exit;
    }

    runMigrations($conn);

    $stmtEmail = $conn->prepare("
        SELECT id
        FROM usuarios
        WHERE email = :email
        LIMIT 1
    ");
    $stmtEmail->bindValue(':email', $email, PDO::PARAM_STR);
    $stmtEmail->execute();

    $usuarioExistente = $stmtEmail->fetch(PDO::FETCH_ASSOC);

    if ($usuarioExistente) {
        http_response_code(409);
        echo json_encode([
            'success' => false,
            'message' => 'Este email já está cadastrado'
        ]);
        exit;
    }

    $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

    if ($senhaHash === false) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'message' => 'Erro ao processar a solicitação'
        ]);
        exit;
    }

    $stmtInsert = $conn->prepare("
        INSERT INTO usuarios (nome, email, senha)
        VALUES (:nome, :email, :senha)
    ");

    $stmtInsert->bindValue(':nome', $nome, PDO::PARAM_STR);
    $stmtInsert->bindValue(':email', $email, PDO::PARAM_STR);
    $stmtInsert->bindValue(':senha', $senhaHash, PDO::PARAM_STR);

    if ($stmtInsert->execute()) {
        http_response_code(201);
        echo json_encode([
            'success' => true,
            'message' => 'Conta criada com sucesso'
        ]);
        exit;
    }

    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erro ao cadastrar usuário'
    ]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => 'Erro interno ao processar o cadastro'
    ]);
}