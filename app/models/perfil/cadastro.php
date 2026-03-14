<?php
session_start();
require_once __DIR__ . '../../../config/database.php';
require_once __DIR__ . '../../../config/migrations.php';

header('Content-Type: application/json');

$input = json_decode(file_get_contents('php://input'), true);

$nome = trim($input['nome'] ?? '');
$email = trim($input['email'] ?? '');
$senha = $input['senha'] ?? '';

if (empty($nome) || empty($email) || empty($senha)) {
    echo json_encode(['success' => false, 'message' => 'Por favor, preencha todos os campos']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Email inválido']);
    exit;
}

if (strlen($senha) < 4) {
    echo json_encode(['success' => false, 'message' => 'A senha precisa ter no mínimo 4 caracteres']);
    exit;
}

$db = new Database();
$conn = $db->getConnection();

if (!$conn) {
    echo json_encode(['success' => false, 'message' => 'Erro de conexão com o banco']);
    exit;
}

runMigrations($conn);

$stmtEmail = $conn->prepare("SELECT id FROM usuarios WHERE email = :email");
$stmtEmail->bindParam(':email', $email);
$stmtEmail->execute();

if ($stmtEmail->rowCount() > 0) {
    echo json_encode(['success' => false, 'message' => 'Este email já está cadastrado']);
    exit;
}

$senhaHash = password_hash($senha, PASSWORD_DEFAULT);

$stmtInsert = $conn->prepare("
    INSERT INTO usuarios (nome, email, senha)
    VALUES (:nome, :email, :senha)
");

$stmtInsert->bindParam(':nome', $nome);
$stmtInsert->bindParam(':email', $email);
$stmtInsert->bindParam(':senha', $senhaHash);

if ($stmtInsert->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Erro ao cadastrar usuário']);
}