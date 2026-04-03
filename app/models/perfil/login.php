<?php
$https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');

session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => '',
    'secure' => $https,
    'httponly' => true,
    'samesite' => 'Lax'
]);

ini_set('session.use_only_cookies', '1');
ini_set('session.use_strict_mode', '1');

session_start();

header('Content-Type: application/json; charset=utf-8');

/*
|--------------------------------------------------------------------------
| CORS
|--------------------------------------------------------------------------
| Se o login roda no mesmo domínio do site, remova CORS.
| Se precisar mesmo de CORS, troque pela sua origem exata.
*/
// header('Access-Control-Allow-Origin: https://seudominio.com');
// header('Access-Control-Allow-Headers: Content-Type');
// header('Access-Control-Allow-Methods: POST, OPTIONS');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

require_once __DIR__ . '../../../config/database.php';
require_once __DIR__ . '../../../config/migrations.php';

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        echo json_encode([
            'success' => false,
            'error' => 'requisicao_invalida'
        ]);
        exit;
    }

    $email = mb_strtolower(trim((string) ($_POST['email'] ?? '')));
    $senha = (string) ($_POST['senha'] ?? '');

    if ($email === '' || $senha === '') {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'error' => 'dados_incompletos'
        ]);
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo json_encode([
            'success' => false,
            'error' => 'email_invalido'
        ]);
        exit;
    }

    $db = new Database();
    $conn = $db->getConnection();

    if (!$conn) {
        http_response_code(500);
        echo json_encode([
            'success' => false,
            'error' => 'erro_conexao'
        ]);
        exit;
    }

    runMigrations($conn);

    $stmt = $conn->prepare("
        SELECT id, nome, email, senha, telefone, tipo_usuario
        FROM usuarios
        WHERE email = :email
        LIMIT 1
    ");
    $stmt->bindValue(':email', $email, PDO::PARAM_STR);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user || !password_verify($senha, $user['senha'])) {
        http_response_code(401);
        echo json_encode([
            'success' => false,
            'error' => 'credenciais_invalidas'
        ]);
        exit;
    }

    if (password_needs_rehash($user['senha'], PASSWORD_DEFAULT)) {
        $novoHash = password_hash($senha, PASSWORD_DEFAULT);

        if ($novoHash !== false) {
            $stmtUpdate = $conn->prepare("
                UPDATE usuarios
                SET senha = :senha, atualizado_em = NOW()
                WHERE id = :id
                LIMIT 1
            ");
            $stmtUpdate->bindValue(':senha', $novoHash, PDO::PARAM_STR);
            $stmtUpdate->bindValue(':id', (int) $user['id'], PDO::PARAM_INT);
            $stmtUpdate->execute();
        }
    }

    session_regenerate_id(true);

    $_SESSION['user_logged_in'] = true;
    $_SESSION['user_id'] = (int) $user['id'];
    $_SESSION['is_admin'] = ((int) ($user['tipo_usuario'] ?? 0) >= 2);
    $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'] ?? '';
    $_SESSION['last_regeneration'] = time();

    http_response_code(200);
    echo json_encode([
        'success' => true,
        'usuario' => [
            'id' => (int) $user['id'],
            'nome' => $user['nome'] ?? '',
            'email' => $user['email'] ?? '',
            'telefone' => $user['telefone'] ?? '',
            'tipo_usuario' => (int) ($user['tipo_usuario'] ?? 0)
        ]
    ]);
    exit;

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'erro_interno'
    ]);
    exit;
}