<?php
session_start();
require_once __DIR__ . '../../../config/database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Requisição inválida.']);
    exit;
}

if (empty($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Usuário não está logado.']);
    exit;
}

$campos = [];
$params = [];

if (isset($_POST['nome'])) {
    $novoNome = trim($_POST['nome']);
    if ($novoNome === '') {
        echo json_encode(['success' => false, 'message' => 'O nome não pode estar vazio.']);
        exit;
    }
    $campos[] = "nome = :nome";
    $params[':nome'] = $novoNome;
}

if (isset($_POST['email'])) {
    $novoEmail = trim($_POST['email']);
    if ($novoEmail === '' || !filter_var($novoEmail, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Informe um email válido.']);
        exit;
    }
    $campos[] = "email = :email";
    $params[':email'] = $novoEmail;
}

if (isset($_POST['telefone'])) {
    $novoTelefone = trim($_POST['telefone']);
    $campos[] = "telefone = :telefone";
    $params[':telefone'] = $novoTelefone;
}

if (empty($campos)) {
    echo json_encode(['success' => false, 'message' => 'Nenhum campo para atualizar.']);
    exit;
}

try {
    $db = new Database();
    $conn = $db->getConnection();

    $sql = "UPDATE usuarios SET " . implode(", ", $campos) . ", atualizado_em = NOW() WHERE id = :id";
    $stmt = $conn->prepare($sql);
    $params[':id'] = $_SESSION['user_id'];

    foreach ($params as $key => $value) {
        $stmt->bindValue($key, $value);
    }

    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Nenhuma alteração feita.']);
    }

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Erro no servidor: ' . $e->getMessage()]);
}