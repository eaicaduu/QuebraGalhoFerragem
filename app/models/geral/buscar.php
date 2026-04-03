<?php
function buscar(
    string $tabela,
    int $id,
    array|string $campos = '*',
    string $join = '',
    string $campoId = 'id'
): ?array {
    $db = new Database();
    $pdo = $db->getConnection();

    if (!$pdo) {
        return null;
    }

    runMigrations($pdo);

    if (!preg_match('/^[a-zA-Z0-9_]+$/', $tabela)) {
        return null;
    }

    if (!preg_match('/^[a-zA-Z0-9_\.]+$/', $campoId)) {
        return null;
    }

    if (is_array($campos)) {
        if (empty($campos)) {
            $select = '*';
        } else {
            $camposValidos = [];

            foreach ($campos as $campo) {
                if (is_string($campo) && preg_match('/^[a-zA-Z0-9_\.\s]+$/', $campo)) {
                    $camposValidos[] = trim($campo);
                }
            }

            if (empty($camposValidos)) {
                return null;
            }

            $select = implode(', ', $camposValidos);
        }
    } else {
        $campos = trim($campos);

        if ($campos !== '*' && !preg_match('/^[a-zA-Z0-9_,\.\s]+$/', $campos)) {
            return null;
        }

        $select = $campos;
    }

    $join = trim($join);

    if ($join !== '') {
        $joinPermitido = preg_match(
            '/^(LEFT|RIGHT|INNER|FULL|CROSS)?\s*JOIN\s+[a-zA-Z0-9_]+\s+(ON)\s+.+$/i',
            $join
        );

        if (!$joinPermitido) {
            return null;
        }
    }

    $sql = "SELECT {$select} FROM {$tabela} {$join} WHERE {$campoId} = :id LIMIT 1";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

    return $resultado ?: null;
}