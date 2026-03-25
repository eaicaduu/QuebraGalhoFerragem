<?php
require_once __DIR__ . '/database.php';
require_once __DIR__ . '/migrations.php';

$user = '';
$isAdmin = false;

if (isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true) {

    $db = new Database();
    $conn = $db->getConnection();

    runMigrations($conn);

    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE id = :id LIMIT 1");
    $stmt->bindParam(':id', $_SESSION['user_id'], PDO::PARAM_INT);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (isset($user) && in_array($user['tipo_usuario'], [2,3])) {
        $isAdmin = true;
    }
}