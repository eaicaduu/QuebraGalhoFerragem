<?php
function buscarProduto(int $id): ?array
{
    $db = new Database();
    $pdo = $db->getConnection();

    $stmt = $pdo->prepare("SELECT * FROM produtos WHERE id = :id LIMIT 1");
    $stmt->execute([':id' => $id]);

    $produto = $stmt->fetch(PDO::FETCH_ASSOC);

    return $produto ?: null;
}