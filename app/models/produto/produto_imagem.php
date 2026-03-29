<?php

function baixarImagemDaUrl(
    string $url,
    string $diretorioFisico,
    string $diretorioBanco,
    string $prefixo = 'produto_'
): string {
    $url = trim($url);

    if ($url === '' || !filter_var($url, FILTER_VALIDATE_URL)) {
        throw new Exception('Informe uma URL de imagem válida.');
    }

    $partes = parse_url($url);

    if (
        empty($partes['scheme']) ||
        !in_array(strtolower($partes['scheme']), ['http', 'https'], true)
    ) {
        throw new Exception('A URL da imagem deve usar HTTP ou HTTPS.');
    }

    if (empty($partes['host'])) {
        throw new Exception('Host da imagem inválido.');
    }

    $host = strtolower($partes['host']);

    if (in_array($host, ['localhost', '127.0.0.1', '::1'], true)) {
        throw new Exception('Host da imagem não permitido.');
    }

    $ips = @gethostbynamel($host);
    if (is_array($ips)) {
        foreach ($ips as $ip) {
            if (
                !filter_var(
                    $ip,
                    FILTER_VALIDATE_IP,
                    FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
                )
            ) {
                throw new Exception('Endereço da imagem não permitido.');
            }
        }
    }

    if (!is_dir($diretorioFisico) && !mkdir($diretorioFisico, 0775, true)) {
        throw new Exception('Não foi possível criar a pasta de upload.');
    }

    $tmpArquivo = tempnam(sys_get_temp_dir(), 'img_');
    if ($tmpArquivo === false) {
        throw new Exception('Não foi possível criar arquivo temporário.');
    }

    $conteudoBaixado = false;
    $erroDownload = '';

    if (function_exists('curl_init')) {
        $fp = fopen($tmpArquivo, 'wb');

        if ($fp !== false) {
            $ch = curl_init($url);

            curl_setopt_array($ch, [
                CURLOPT_FILE => $fp,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_MAXREDIRS => 3,
                CURLOPT_CONNECTTIMEOUT => 10,
                CURLOPT_TIMEOUT => 20,
                CURLOPT_PROTOCOLS => CURLPROTO_HTTP | CURLPROTO_HTTPS,
                CURLOPT_REDIR_PROTOCOLS => CURLPROTO_HTTP | CURLPROTO_HTTPS,
                CURLOPT_SSL_VERIFYPEER => true,
                CURLOPT_SSL_VERIFYHOST => 2,
                CURLOPT_USERAGENT => 'Mozilla/5.0 ProdutoImageFetcher/1.0',
                CURLOPT_NOPROGRESS => false,
                CURLOPT_PROGRESSFUNCTION => function ($resource, $downloadSize, $downloaded) {
                    $limite = 5 * 1024 * 1024; // 5 MB
                    if ($downloaded > $limite) {
                        return 1;
                    }
                    return 0;
                },
            ]);

            $ok = curl_exec($ch);
            $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $erroCurl = curl_error($ch);

            curl_close($ch);
            fclose($fp);

            if ($ok !== false && $httpCode >= 200 && $httpCode < 300 && is_file($tmpArquivo) && filesize($tmpArquivo) > 0) {
                $conteudoBaixado = true;
            } else {
                $erroDownload = $erroCurl !== '' ? $erroCurl : 'Falha ao baixar a imagem.';
                @unlink($tmpArquivo);
                $tmpArquivo = tempnam(sys_get_temp_dir(), 'img_');
                if ($tmpArquivo === false) {
                    throw new Exception('Não foi possível criar arquivo temporário.');
                }
            }
        }
    }

    if ($conteudoBaixado === false) {
        $contexto = stream_context_create([
            'http' => [
                'method' => 'GET',
                'timeout' => 20,
                'follow_location' => 1,
                'user_agent' => 'Mozilla/5.0 ProdutoImageFetcher/1.0',
            ],
            'ssl' => [
                'verify_peer' => true,
                'verify_peer_name' => true,
            ],
        ]);

        $dados = @file_get_contents($url, false, $contexto);

        if ($dados === false) {
            @unlink($tmpArquivo);
            throw new Exception(
                'Não foi possível baixar a imagem. O servidor da URL pode estar com SSL incompatível ou bloqueando o download.'
            );
        }

        if (strlen($dados) > 5 * 1024 * 1024) {
            @unlink($tmpArquivo);
            throw new Exception('A imagem excede o limite de 5 MB.');
        }

        if (file_put_contents($tmpArquivo, $dados) === false) {
            @unlink($tmpArquivo);
            throw new Exception('Não foi possível salvar a imagem temporária.');
        }

        $conteudoBaixado = true;
    }

    if (!$conteudoBaixado || !is_file($tmpArquivo) || filesize($tmpArquivo) <= 0) {
        @unlink($tmpArquivo);
        throw new Exception(
            $erroDownload !== ''
            ? 'Não foi possível baixar a imagem: ' . $erroDownload
            : 'A imagem baixada está vazia.'
        );
    }

    $mimeReal = mime_content_type($tmpArquivo);

    $mimesPermitidos = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
    ];

    if (!isset($mimesPermitidos[$mimeReal])) {
        @unlink($tmpArquivo);
        throw new Exception('A URL informada não contém uma imagem JPG, PNG ou WEBP válida.');
    }

    if (@getimagesize($tmpArquivo) === false) {
        @unlink($tmpArquivo);
        throw new Exception('O arquivo baixado não é uma imagem válida.');
    }

    $extensao = $mimesPermitidos[$mimeReal];
    $nomeArquivo = uniqid($prefixo, true) . '.' . $extensao;

    $caminhoFisicoFinal = rtrim($diretorioFisico, '/\\') . DIRECTORY_SEPARATOR . $nomeArquivo;
    $caminhoBanco = trim($diretorioBanco, '/\\') . '/' . $nomeArquivo;

    if (!rename($tmpArquivo, $caminhoFisicoFinal)) {
        @unlink($tmpArquivo);
        throw new Exception('Não foi possível salvar a imagem baixada.');
    }

    return $caminhoBanco;
}