<?php
function buscar(string $tabela, int $id): ?array
{
    $db = new Database();
    $pdo = $db->getConnection();

    if (!$pdo)
        return null;

    $sql = "SELECT * FROM {$tabela} WHERE id = :id LIMIT 1";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':id' => $id
    ]);

    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

    return $resultado ?: null;
}