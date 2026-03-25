<?php
function listarCarousel(bool $somenteAtivos = false): array
{
    $db = new Database();
    $pdo = $db->getConnection();

    if (!$pdo) {
        return [];
    }

    runMigrations($pdo);

    $sql = "SELECT * FROM carousel";

    if ($somenteAtivos) {
        $sql .= " WHERE ativo = 1";
    }

    $sql .= " ORDER BY id DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}