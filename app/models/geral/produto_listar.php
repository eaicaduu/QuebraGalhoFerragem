<?php
function listarProdutos(?string $pesquisa = null, string $contexto = 'usuario'): array
{
    $db = new Database();
    $pdo = $db->getConnection();

    if (!$pdo) {
        return [];
    }

    runMigrations($pdo);

    $pesquisa = trim((string) $pesquisa);

    $sql = "
        SELECT *
        FROM produtos
        WHERE 1 = 1
    ";

    $params = [];

    if ($contexto === 'usuario') {
        $sql .= " AND ativo = 1";
    }

    if ($pesquisa !== '') {
        $sql .= "
            AND (
                nome LIKE :pesquisa_nome
                OR descricao LIKE :pesquisa_descricao
                OR CAST(preco AS CHAR) LIKE :pesquisa_preco
            )
        ";

        $valorPesquisa = '%' . $pesquisa . '%';

        $params[':pesquisa_nome'] = $valorPesquisa;
        $params[':pesquisa_descricao'] = $valorPesquisa;
        $params[':pesquisa_preco'] = $valorPesquisa;
    }

    $sql .= " ORDER BY id DESC";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}