<?php
function listar(
    string $tabela,
    ?string $pesquisa = null,
    bool $ativos = false,
    string $order = 'id DESC',
    array $camposPesquisa = [],
    string $select = '*',
    string $join = '',
    string $groupBy = ''
): array {
    $db = new Database();
    $pdo = $db->getConnection();

    if (!$pdo) return [];

    runMigrations($pdo);

    $pesquisa = trim((string) $pesquisa);

    $sql = "SELECT {$select} FROM {$tabela} {$join} WHERE 1 = 1";
    $params = [];

    if ($ativos) {
        $sql .= " AND ativo = 1";
    }

    if ($pesquisa !== '' && !empty($camposPesquisa)) {
        $likes = [];

        foreach ($camposPesquisa as $index => $campo) {
            $param = ":pesquisa{$index}";
            $likes[] = "{$campo} LIKE {$param}";
            $params[$param] = '%' . $pesquisa . '%';
        }

        $sql .= " AND (" . implode(' OR ', $likes) . ")";
    }

    if ($groupBy !== '') {
        $sql .= " GROUP BY {$groupBy}";
    }

    $sql .= " ORDER BY {$order}";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}