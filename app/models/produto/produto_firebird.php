<?php
session_start();

header('Content-Type: application/json; charset=UTF-8');

function paraUtf8($valor)
{
    $valor = trim((string) $valor);

    if ($valor === '') {
        return '';
    }

    return mb_convert_encoding($valor, 'UTF-8', 'ISO-8859-1');
}

set_time_limit(0);
ini_set('memory_limit', '2048M');

try {
    $filtroAtivo = $_POST['filtro_ativo'] ?? 'todos';
    $filtroPercentual = ($_POST['filtro_percentual'] ?? '0') === '1';
    $filtroGtin = $_POST['filtro_gtin'] ?? 'todos';
    $filtroEstoqueMin = $_POST['filtro_estoque_min'] ?? '';
    $filtroEstoqueMax = $_POST['filtro_estoque_max'] ?? '';

    $filtros = [];

    if ($filtroAtivo === 'ativos') {
        $filtros[] = 'ATIVO = 1';
    } elseif ($filtroAtivo === 'inativos') {
        $filtros[] = 'ATIVO = 0';
    }

    if ($filtroPercentual) {
        $filtros[] = "(DESCRICAO LIKE '%\\%%' ESCAPE '\\')";
    }

    if ($filtroGtin === 'com_gtin') {
        $filtros[] = "
            REFERENCIA IS NOT NULL
            AND TRIM(REFERENCIA) <> ''
            AND UPPER(TRIM(REFERENCIA)) NOT LIKE '%SEM%'
        ";
    } elseif ($filtroGtin === 'sem_gtin') {
        $filtros[] = "
            REFERENCIA IS NULL
            OR TRIM(REFERENCIA) = ''
            OR UPPER(TRIM(REFERENCIA)) LIKE '%SEM%'
        ";
    }

    if ($filtroEstoqueMin !== '' && is_numeric($filtroEstoqueMin)) {
        $filtros[] = "QTD_ATUAL >= " . (float) $filtroEstoqueMin;
    }

    if ($filtroEstoqueMax !== '' && is_numeric($filtroEstoqueMax)) {
        $filtros[] = "QTD_ATUAL <= " . (float) $filtroEstoqueMax;
    }

    $whereSql = '';
    if (!empty($filtros)) {
        $whereSql = 'WHERE ' . implode(' AND ', $filtros);
    }

    $caminhoFdb = realpath(__DIR__ . '/../../config/small.fdb');

    if (!$caminhoFdb || !file_exists($caminhoFdb)) {
        throw new Exception('Arquivo Firebird não encontrado.');
    }

    $isql = 'C:\\Program Files\\Firebird\\Firebird_2_5\\bin\\isql.exe';

    if (!file_exists($isql)) {
        throw new Exception('isql.exe não encontrado.');
    }

    $separador = '~|~';

    $sqlTemp = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'consulta_' . uniqid() . '.sql';
    $saidaTemp = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'saida_' . uniqid() . '.txt';

    $sql = <<<SQL
    SET SQL DIALECT 3;
    SET NAMES WIN1252;
    SET HEADING OFF;
    SET LIST OFF;

    SELECT
        COALESCE(CAST(CODIGO AS VARCHAR(50)), '') || '$separador' ||
        COALESCE(CAST(REFERENCIA AS VARCHAR(255)), '') || '$separador' ||
        COALESCE(CAST(DESCRICAO AS VARCHAR(500)), '') || '$separador' ||
        COALESCE(CAST(NOME AS VARCHAR(255)), '') || '$separador' ||
        COALESCE(CAST(FORNECEDOR AS VARCHAR(255)), '') || '$separador' ||
        COALESCE(CAST(MEDIDA AS VARCHAR(50)), '') || '$separador' ||
        COALESCE(CAST(PRECO AS VARCHAR(50)), '') || '$separador' ||
        COALESCE(CAST(CUSTOCOMPR AS VARCHAR(50)), '') || '$separador' ||
        COALESCE(CAST(QTD_ATUAL AS VARCHAR(50)), '') || '$separador' ||
        COALESCE(CAST(QTD_MINIM AS VARCHAR(50)), '') || '$separador' ||
        COALESCE(CAST(ATIVO AS VARCHAR(50)), '')
    FROM ESTOQUE
    {$whereSql}
    ORDER BY CODIGO;
    SQL;

    file_put_contents($sqlTemp, $sql);

    $cmd = '"' . $isql . '"' .
        ' -user SYSDBA' .
        ' -password masterkey' .
        ' -ch WIN1252' .
        ' -i "' . $sqlTemp . '"' .
        ' -o "' . $saidaTemp . '"' .
        ' "' . $caminhoFdb . '"';

    exec($cmd);

    $linhas = file($saidaTemp, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    $rows = [];

    foreach ($linhas as $linha) {
        if (strpos($linha, $separador) === false) continue;

        $v = explode($separador, $linha);

        $rows[] = [
            'CODIGO' => paraUtf8($v[0] ?? ''),
            'REFERENCIA' => paraUtf8($v[1] ?? ''),
            'DESCRICAO' => paraUtf8($v[2] ?? ''),
            'NOME' => paraUtf8($v[3] ?? ''),
            'FORNECEDOR' => paraUtf8($v[4] ?? ''),
            'MEDIDA' => paraUtf8($v[5] ?? ''),
            'PRECO' => (float) ($v[6] ?? 0),
            'CUSTOCOMPR' => (float) ($v[7] ?? 0),
            'QTD_ATUAL' => (float) ($v[8] ?? 0),
            'QTD_MINIM' => (float) ($v[9] ?? 0),
            'ATIVO' => (int) ($v[10] ?? 0),
        ];
    }

    @unlink($sqlTemp);
    @unlink($saidaTemp);

    $_SESSION['import_rows'] = $rows;
    $_SESSION['firebird_filtro_ativo'] = $filtroAtivo;
    $_SESSION['firebird_filtro_percentual'] = $filtroPercentual;

    echo json_encode([
        'status' => 'success',
        'total' => count($rows)
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'erro' => $e->getMessage()
    ]);
}
