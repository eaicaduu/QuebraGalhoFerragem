<?php
function buscarCarousel(int $id): ?array
{
    $db = new Database();
    $pdo = $db->getConnection();

    $stmt = $pdo->prepare("SELECT * FROM carousel WHERE id = :id LIMIT 1");
    $stmt->execute([':id' => $id]);

    $item = $stmt->fetch(PDO::FETCH_ASSOC);

    return $item ?: null;
}