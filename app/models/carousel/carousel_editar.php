<?php
header('Content-Type: application/json');

require_once __DIR__ . '../../../config/database.php';

try {
    $db = new Database();
    $pdo = $db->getConnection();

    $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
    $ativo = isset($_POST['ativo_padrao']) ? (int) $_POST['ativo_padrao'] : 1;
    $imagemAtual = isset($_POST['imagem_atual']) ? trim((string) $_POST['imagem_atual']) : '';

    if ($id <= 0) {
        throw new Exception('ID inválido.');
    }

    $imagemBanco = $imagemAtual;

    if (isset($_FILES['imagens']) && !empty($_FILES['imagens']['name'][0])) {
        if ($_FILES['imagens']['error'][0] !== UPLOAD_ERR_OK) {
            throw new Exception('Erro ao enviar a nova imagem.');
        }

        $tmpName = $_FILES['imagens']['tmp_name'][0];
        $type = mime_content_type($tmpName);

        $permitidas = ['image/jpeg', 'image/png', 'image/webp', 'image/jpg', 'image/gif'];

        if (!in_array($type, $permitidas, true)) {
            throw new Exception('Formato de imagem inválido.');
        }

        $diretorioFisico = __DIR__ . '/../../uploads/carousel/';
        $diretorioBanco = 'uploads/carousel/';

        if (!is_dir($diretorioFisico)) {
            mkdir($diretorioFisico, 0777, true);
        }

        $nomeOriginal = $_FILES['imagens']['name'][0];
        $extensao = strtolower(pathinfo($nomeOriginal, PATHINFO_EXTENSION));
        $nomeArquivo = uniqid('carousel_', true) . '.' . $extensao;

        $caminhoFisico = $diretorioFisico . $nomeArquivo;
        $imagemBanco = $diretorioBanco . $nomeArquivo;

        if (!move_uploaded_file($tmpName, $caminhoFisico)) {
            throw new Exception('Não foi possível salvar a nova imagem.');
        }

        if (!empty($imagemAtual)) {
            $imagemFisicaAtual = __DIR__ . '/../../' . ltrim($imagemAtual, '/');

            if (file_exists($imagemFisicaAtual) && is_file($imagemFisicaAtual)) {
                unlink($imagemFisicaAtual);
            }
        }
    }

    $stmt = $pdo->prepare("
        UPDATE carousel
        SET imagem = :imagem, ativo = :ativo
        WHERE id = :id
    ");

    $stmt->execute([
        ':id' => $id,
        ':imagem' => $imagemBanco,
        ':ativo' => $ativo
    ]);

    echo json_encode([
        'status' => true,
        'mensagem' => 'Imagem atualizada com sucesso.',
        'redirect' => 'admin.php?page=configuracoes&acao=carousel'
    ]);
    exit;

} catch (Throwable $e) {
    http_response_code(400);

    echo json_encode([
        'status' => false,
        'mensagem' => $e->getMessage()
    ]);
    exit;
}