<?php
session_start();

require_once __DIR__ . '../../../config/vendor/autoload.php';

use Smalot\PdfParser\Parser;

header('Content-Type: application/json; charset=utf-8');

try {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new RuntimeException('Requisição inválida.');
    }

    if (
        !isset($_FILES['arquivo_importacao']) ||
        !is_array($_FILES['arquivo_importacao']) ||
        empty($_FILES['arquivo_importacao']['name'])
    ) {
        throw new RuntimeException('Selecione um arquivo para importar.');
    }

    $arquivo = $_FILES['arquivo_importacao'];

    if (!isset($arquivo['error']) || $arquivo['error'] !== UPLOAD_ERR_OK) {
        throw new RuntimeException('Falha no envio do arquivo.');
    }

    $ext = strtolower(pathinfo((string) $arquivo['name'], PATHINFO_EXTENSION));

    if (!in_array($ext, ['pdf', 'txt'], true)) {
        throw new RuntimeException('Formato inválido. Envie apenas PDF ou TXT.');
    }

    unset(
        $_SESSION['import_headers'],
        $_SESSION['import_rows'],
        $_SESSION['import_preview_produtos'],
        $_SESSION['import_arquivo_nome']
    );

    if ($ext === 'pdf') {
        $parser = new Parser();
        $pdf = $parser->parseFile($arquivo['tmp_name']);
        $texto = $pdf->getText();
    } else {
        $texto = file_get_contents($arquivo['tmp_name']);
    }

    if (!is_string($texto) || trim($texto) === '') {
        throw new RuntimeException('Não foi possível ler o conteúdo do arquivo.');
    }

    $linhas = preg_split('/\r\n|\r|\n/', $texto);
    $linhas = array_map(
        static fn($linha) => trim((string) $linha),
        $linhas ?: []
    );
    $linhas = array_values(array_filter(
        $linhas,
        static fn($linha) => $linha !== ''
    ));

    if (empty($linhas)) {
        throw new RuntimeException('O arquivo não possui linhas válidas.');
    }

    $headerIndex = null;

    foreach ($linhas as $i => $linha) {
        $linhaNormalizada = mb_strtolower($linha, 'UTF-8');

        $temCodigo = mb_stripos($linhaNormalizada, 'código') !== false
            || mb_stripos($linhaNormalizada, 'codigo') !== false;

        $temDescricao = mb_stripos($linhaNormalizada, 'descrição') !== false
            || mb_stripos($linhaNormalizada, 'descricao') !== false;

        if ($temCodigo && $temDescricao) {
            $headerIndex = $i;
            break;
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

    for ($i = $headerIndex + 1, $totalLinhas = count($linhas); $i < $totalLinhas; $i++) {
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

    echo json_encode([
        'status' => 'success',
        'mensagem' => 'Arquivo processado com sucesso.',
        'quantidade_linhas' => count($rows),
        'visualizar_url' => 'admin.php?page=importar&acao=visualizar'
    ], JSON_UNESCAPED_UNICODE);
    exit;

} catch (Throwable $e) {
    http_response_code(400);

    echo json_encode([
        'status' => 'error',
        'mensagem' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
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
    $partes = array_map(
        static fn($parte) => trim((string) $parte),
        $partes ?: []
    );

    return array_values($partes);
}