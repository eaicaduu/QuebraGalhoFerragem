<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../../admin.php?page=importar&acao=importar');
    exit;
}

$headers = $_SESSION['import_headers'] ?? [];
$rows = $_SESSION['import_rows'] ?? [];

if (empty($headers) || empty($rows)) {
    $_SESSION['import_erro'] = 'Nenhum arquivo carregado para processar.';
    header('Location: ../../../admin.php?page=importar&acao=importar');
    exit;
}

$preview = [];

foreach ($rows as $row) {
    $produto = [];

    foreach ($row as $campo => $valor) {
        $valor = trim((string) $valor);

        if (in_array($campo, ['preco', 'custo_compra', 'quantidade'], true)) {
            $produto[$campo] = normalizarNumeroImportacao($valor);
            continue;
        }

        if ($campo === 'ultima_compra') {
            $produto[$campo] = normalizarDataImportacao($valor) ?? $valor;
            continue;
        }

        $produto[$campo] = $valor;
    }

    if (!linhaPreviewValida($produto)) {
        continue;
    }

    $preview[] = $produto;
}

if (empty($preview)) {
    $_SESSION['import_erro'] = 'Nenhum dado válido foi gerado para pré-visualização.';
    header('Location: ../../../admin.php?page=importar&acao=importar');
    exit;
}

$_SESSION['import_preview_produtos'] = $preview;

header('Location: ../../../admin.php?page=importar&acao=importar');
exit;

function normalizarNumeroImportacao(string $valor): float
{
    $valor = trim($valor);

    if ($valor === '') {
        return 0;
    }

    if (preg_match('/^\d+(,\d+)?$/', $valor)) {
        return (float) str_replace(',', '.', $valor);
    }

    $valor = str_replace('.', '', $valor);
    $valor = str_replace(',', '.', $valor);

    return is_numeric($valor) ? (float) $valor : 0;
}

function normalizarDataImportacao(string $valor): ?string
{
    $valor = trim($valor);

    if ($valor === '') {
        return null;
    }

    if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $valor)) {
        [$d, $m, $y] = explode('/', $valor);
        return $y . '-' . $m . '-' . $d;
    }

    return null;
}

function linhaPreviewValida(array $produto): bool
{
    foreach ($produto as $valor) {
        if ((string) $valor !== '' && (string) $valor !== '0') {
            return true;
        }
    }

    return false;
}