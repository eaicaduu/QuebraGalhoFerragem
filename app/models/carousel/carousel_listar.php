<?php
function listarCarousel(): array
{
    $db = new Database();
    $pdo = $db->getConnection();

    if (!$pdo) {
        return [];
    }

    runMigrations($pdo);

    $stmt = $pdo->prepare("SELECT * FROM carousel ORDER BY id DESC");
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}