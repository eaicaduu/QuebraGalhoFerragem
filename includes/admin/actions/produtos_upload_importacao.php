<?php
session_start();

require_once __DIR__ . '../../../../app/config/vendor/autoload.php';

use Smalot\PdfParser\Parser;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../../admin.php?page=importar&acao=importar');
    exit;
}

unset(
    $_SESSION['import_headers'],
    $_SESSION['import_rows'],
    $_SESSION['import_preview_produtos'],
    $_SESSION['import_arquivo_nome'],
    $_SESSION['import_erro']
);

if (empty($_FILES['arquivo_importacao']['name'])) {
    $_SESSION['import_erro'] = 'Selecione um arquivo para importar.';
    header('Location: ../../../admin.php?page=importar&acao=importar');
    exit;
}

$arquivo = $_FILES['arquivo_importacao'];
$ext = strtolower(pathinfo($arquivo['name'], PATHINFO_EXTENSION));

if (!in_array($ext, ['pdf', 'txt'], true)) {
    $_SESSION['import_erro'] = 'Formato inválido. Envie apenas PDF ou TXT.';
    header('Location: ../../../admin.php?page=importar&acao=importar');
    exit;
}

try {
    if ($ext === 'pdf') {
        $parser = new Parser();
        $pdf = $parser->parseFile($arquivo['tmp_name']);
        $texto = $pdf->getText();
    } else {
        $texto = file_get_contents($arquivo['tmp_name']);
    }

    if (!$texto || trim($texto) === '') {
        throw new RuntimeException('Não foi possível ler o conteúdo do arquivo.');
    }

    $linhas = preg_split('/\r\n|\r|\n/', $texto);
    $linhas = array_map(fn($l) => trim((string) $l), $linhas);
    $linhas = array_values(array_filter($linhas, fn($l) => $l !== ''));

    if (empty($linhas)) {
        throw new RuntimeException('O arquivo não possui linhas válidas.');
    }

    $headerIndex = null;
    foreach ($linhas as $i => $linha) {
        if (
            mb_stripos($linha, 'código') !== false ||
            mb_stripos($linha, 'codigo') !== false
        ) {
            if (
                mb_stripos($linha, 'descrição') !== false ||
                mb_stripos($linha, 'descricao') !== false
            ) {
                $headerIndex = $i;
                break;
            }
        }
    }

    if ($headerIndex === null) {
        throw new RuntimeException('Não foi possível localizar a linha de cabeçalho do arquivo.');
    }

    $headerLine = limparLinhaImportacao($linhas[$headerIndex]);
    $headers = dividirColunas($headerLine);

    if (count($headers) < 2) {
        throw new RuntimeException('Não foi possível identificar as colunas do arquivo.');
    }

    $rows = [];

    for ($i = $headerIndex + 1; $i < count($linhas); $i++) {
        $linha = limparLinhaImportacao($linhas[$i]);

        if ($linha === '') {
            continue;
        }

        if (preg_match('/^-{3,}/', $linha)) {
            continue;
        }

        $colunas = dividirColunas($linha);

        if (count($colunas) < 2) {
            continue;
        }

        if (count($colunas) < count($headers)) {
            $faltando = count($headers) - count($colunas);
            for ($j = 0; $j < $faltando; $j++) {
                $colunas[] = '';
            }
        }

        if (count($colunas) > count($headers)) {
            $colunas = array_slice($colunas, 0, count($headers));
        }

        $temConteudo = false;
        foreach ($colunas as $valor) {
            if (trim((string) $valor) !== '') {
                $temConteudo = true;
                break;
            }
        }

        if ($temConteudo) {
            $rows[] = $colunas;
        }
    }

    if (empty($rows)) {
        throw new RuntimeException('Nenhuma linha de dados foi encontrada.');
    }

    $_SESSION['import_headers'] = $headers;
    $_SESSION['import_rows'] = $rows;
    $_SESSION['import_arquivo_nome'] = $arquivo['name'];

    header('Location: ../../../admin.php?page=importar&acao=importar');
    exit;

} catch (Throwable $e) {
    $_SESSION['import_erro'] = $e->getMessage();
    header('Location: ../../../admin.php?page=importar&acao=importar');
    exit;
}

function limparLinhaImportacao(string $linha): string
{
    $linha = preg_replace('/[ ]{2,}/', '  ', $linha);
    return trim((string) $linha);
}

function dividirColunas(string $linha): array
{
    $partes = preg_split('/\s{2,}|\t+/', trim($linha));
    $partes = array_map(fn($p) => trim((string) $p), $partes);
    return array_values($partes);
}