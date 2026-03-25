<?php
header('Content-Type: application/json');

require_once __DIR__ . '../../../config/database.php';

try {
    $db = new Database();
    $pdo = $db->getConnection();

    if (!isset($_FILES['imagens']) || empty($_FILES['imagens']['name'][0])) {
        http_response_code(400);
        echo json_encode([
            'status' => false,
            'mensagem' => 'Nenhuma imagem foi enviada.'
        ]);
        exit;
    }

    $ativoPadrao = isset($_POST['ativo_padrao']) ? (int) $_POST['ativo_padrao'] : 1;

    $diretorioFisico = __DIR__ . '/../../uploads/carousel/';
    $diretorioBanco = 'uploads/carousel/';

    if (!is_dir($diretorioFisico)) {
        mkdir($diretorioFisico, 0777, true);
    }

    $permitidas = ['image/jpeg', 'image/png', 'image/webp', 'image/jpg', 'image/gif'];
    $salvas = 0;

    foreach ($_FILES['imagens']['name'] as $index => $nomeOriginal) {
        if ($_FILES['imagens']['error'][$index] !== UPLOAD_ERR_OK) {
            continue;
        }

        $tmpName = $_FILES['imagens']['tmp_name'][$index];
        $type = mime_content_type($tmpName);

        if (!in_array($type, $permitidas, true)) {
            continue;
        }

        $extensao = pathinfo($nomeOriginal, PATHINFO_EXTENSION);
        $nomeArquivo = uniqid('carousel_', true) . '.' . strtolower($extensao);

        $caminhoFisico = $diretorioFisico . $nomeArquivo;
        $caminhoBanco = $diretorioBanco . $nomeArquivo;

        if (move_uploaded_file($tmpName, $caminhoFisico)) {
            $stmt = $pdo->prepare("
                INSERT INTO carousel (imagem, ativo, criado_em)
                VALUES (:imagem, :ativo, NOW())
            ");

            $stmt->execute([
                ':imagem' => $caminhoBanco,
                ':ativo' => $ativoPadrao
            ]);

            $salvas++;
        }
    }

    if ($salvas === 0) {
        http_response_code(400);
        echo json_encode([
            'status' => false,
            'mensagem' => 'Nenhuma imagem válida foi salva.'
        ]);
        exit;
    }

    echo json_encode([
        'status' => true,
        'mensagem' => 'Imagem salva com sucesso.'
    ]);
    exit;

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'status' => false,
        'mensagem' => 'Erro interno: ' . $e->getMessage()
    ]);
    exit;
}