<?php
session_start();

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

require_once __DIR__ . '../../../config/database.php';
require_once __DIR__ . '../../../config/migrations.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'requisicao_invalida']);
    exit;
}

$email = trim($_POST['email'] ?? '');
$senha = $_POST['senha'] ?? '';

if (empty($email) || empty($senha)) {
    echo json_encode(['success' => false, 'error' => 'dados_incompletos']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'error' => 'email_invalido']);
    exit;
}

$db = new Database();
$conn = $db->getConnection();

if (!$conn) {
    echo json_encode(['success' => false, 'error' => 'erro_conexao']);
    exit;
}

runMigrations($conn);

$stmt = $conn->prepare("SELECT * FROM usuarios WHERE email = :email");
$stmt->bindParam(':email', $email);
$stmt->execute();

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo json_encode(['success' => false, 'error' => 'email_nao_encontrado']);
    exit;
}

if (!password_verify($senha, $user['senha'])) {
    echo json_encode(['success' => false, 'error' => 'senha_incorreta']);
    exit;
}

$_SESSION['user_logged_in'] = true;
$_SESSION['user_id'] = $user['id'];

echo json_encode([
    'success' => true,
    'usuario' => [
        'id' => $user['id'],
        'nome' => $user['nome'],
        'email' => $user['email']
    ]
]);

exit;