<?php
header('Content-Type: application/json');

require_once __DIR__ . '../../../config/database.php';

try {
    $db = new Database();
    $pdo = $db->getConnection();

    $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
    $nome = isset($_POST['nome']) ? trim((string) $_POST['nome']) : '';
    $descricao = isset($_POST['descricao']) ? trim((string) $_POST['descricao']) : '';
    $preco = isset($_POST['preco']) ? (float) $_POST['preco'] : 0;
    $preco_pj = isset($_POST['preco_pj']) && $_POST['preco_pj'] !== '' ? (float) $_POST['preco_pj'] : null;
    $estoque = isset($_POST['estoque']) ? (int) $_POST['estoque'] : 0;
    $ativo = isset($_POST['ativo']) ? (int) $_POST['ativo'] : 1;
    $imagemAtual = isset($_POST['imagem_atual']) ? trim((string) $_POST['imagem_atual']) : '';

    if ($id <= 0) {
        throw new Exception('ID inválido.');
    }

    if ($nome === '') {
        throw new Exception('Informe o nome do produto.');
    }

    if ($preco < 0) {
        throw new Exception('Informe um preço válido.');
    }

    if ($preco_pj !== null && $preco_pj < 0) {
        throw new Exception('Informe um preço PJ válido.');
    }

    if ($estoque < 0) {
        throw new Exception('Informe um estoque válido.');
    }

    if (!in_array($ativo, [0, 1], true)) {
        $ativo = 1;
    }

    $imagemBanco = $imagemAtual !== '' ? $imagemAtual : null;

    if (isset($_FILES['imagem']) && !empty($_FILES['imagem']['name'])) {
        if ($_FILES['imagem']['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('Erro ao enviar a imagem.');
        }

        $tmpName = $_FILES['imagem']['tmp_name'];
        $type = mime_content_type($tmpName);
        $permitidas = ['image/jpeg', 'image/png', 'image/webp', 'image/jpg'];

        if (!in_array($type, $permitidas, true)) {
            throw new Exception('Formato de imagem inválido. Envie JPG, JPEG, PNG ou WEBP.');
        }

        $diretorioFisico = __DIR__ . '/../../uploads/produtos/';
        $diretorioBanco = 'uploads/produtos/';

        if (!is_dir($diretorioFisico)) {
            mkdir($diretorioFisico, 0777, true);
        }

        $nomeOriginal = $_FILES['imagem']['name'];
        $extensao = strtolower(pathinfo($nomeOriginal, PATHINFO_EXTENSION));
        $nomeArquivo = uniqid('produto_', true) . '.' . $extensao;

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
        UPDATE produtos
        SET
            nome = :nome,
            descricao = :descricao,
            preco = :preco,
            preco_pj = :preco_pj,
            estoque = :estoque,
            imagem = :imagem,
            ativo = :ativo,
            atualizado_em = NOW()
        WHERE id = :id
    ");

    $stmt->execute([
        ':id' => $id,
        ':nome' => $nome,
        ':descricao' => $descricao !== '' ? $descricao : null,
        ':preco' => $preco,
        ':preco_pj' => $preco_pj,
        ':estoque' => $estoque,
        ':imagem' => $imagemBanco,
        ':ativo' => $ativo
    ]);

    echo json_encode([
        'status' => true,
        'mensagem' => 'Produto atualizado com sucesso.',
        'redirect' => 'admin.php?page=produtos&acao=todos'
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