<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '../../../config/database.php';
require_once __DIR__ . '/produto_imagem.php';

try {
    $db = new Database();
    $pdo = $db->getConnection();

    $id = isset($_POST['id']) ? (int) $_POST['id'] : 0;
    $nome = isset($_POST['nome']) ? trim((string) $_POST['nome']) : '';
    $categoria_id = isset($_POST['categoria_id']) && $_POST['categoria_id'] !== ''
        ? (int) $_POST['categoria_id']
        : null;
    $descricao = isset($_POST['descricao']) ? trim((string) $_POST['descricao']) : '';
    $preco = isset($_POST['preco']) ? (float) $_POST['preco'] : 0;
    $preco_pj = isset($_POST['preco_pj']) && $_POST['preco_pj'] !== '' ? (float) $_POST['preco_pj'] : null;
    $estoque = isset($_POST['estoque']) ? (int) $_POST['estoque'] : 0;
    $ativo = isset($_POST['ativo']) ? (int) $_POST['ativo'] : 1;
    $imagemAtual = isset($_POST['imagem_atual']) ? trim((string) $_POST['imagem_atual']) : '';
    $imagemUrl = isset($_POST['imagem_url']) ? trim((string) $_POST['imagem_url']) : '';
    $removerImagem = isset($_POST['remover_imagem']) ? (int) $_POST['remover_imagem'] : 0;

    if ($id <= 0) {
        throw new Exception('ID inválido.');
    }

    if ($nome === '') {
        throw new Exception('Informe o nome do produto.');
    }

    if ($categoria_id !== null) {
        $stmtCategoria = $pdo->prepare("SELECT id FROM categorias WHERE id = :id LIMIT 1");
        $stmtCategoria->execute([':id' => $categoria_id]);

        if (!$stmtCategoria->fetch(PDO::FETCH_ASSOC)) {
            throw new Exception('Categoria inválida.');
        }
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

    $diretorioFisico = __DIR__ . '/../../uploads/produtos/';
    $diretorioBanco = 'uploads/produtos/';

    if ($removerImagem === 1) {
        $imagemBanco = null;
    }

    if ($imagemUrl !== '') {
        $novaImagemUrl = baixarImagemDaUrl(
            $imagemUrl,
            $diretorioFisico,
            $diretorioBanco,
            'produto_'
        );

        $imagemBanco = $novaImagemUrl;

        if (!empty($imagemAtual)) {
            $imagemFisicaAtual = __DIR__ . '/../../' . ltrim($imagemAtual, '/');

            if (file_exists($imagemFisicaAtual) && is_file($imagemFisicaAtual)) {
                @unlink($imagemFisicaAtual);
            }
        }
    }

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

        if (!is_dir($diretorioFisico)) {
            mkdir($diretorioFisico, 0775, true);
        }

        $nomeOriginal = $_FILES['imagem']['name'];
        $extensao = strtolower(pathinfo($nomeOriginal, PATHINFO_EXTENSION));
        $extensoesPermitidas = ['jpg', 'jpeg', 'png', 'webp'];

        if (!in_array($extensao, $extensoesPermitidas, true)) {
            throw new Exception('Extensão de imagem inválida.');
        }

        $nomeArquivo = uniqid('produto_', true) . '.' . $extensao;
        $caminhoFisico = $diretorioFisico . $nomeArquivo;
        $imagemBanco = $diretorioBanco . $nomeArquivo;

        if (!move_uploaded_file($tmpName, $caminhoFisico)) {
            throw new Exception('Não foi possível salvar a nova imagem.');
        }

        if (!empty($imagemAtual)) {
            $imagemFisicaAtual = __DIR__ . '/../../' . ltrim($imagemAtual, '/');

            if (file_exists($imagemFisicaAtual) && is_file($imagemFisicaAtual)) {
                @unlink($imagemFisicaAtual);
            }
        }
    }

    if ($removerImagem === 1 && !empty($imagemAtual)) {
        $imagemFisicaAtual = __DIR__ . '/../../' . ltrim($imagemAtual, '/');

        if (file_exists($imagemFisicaAtual) && is_file($imagemFisicaAtual)) {
            @unlink($imagemFisicaAtual);
        }
    }

    $stmt = $pdo->prepare("
        UPDATE produtos
        SET
            categoria_id = :categoria_id,
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

    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->bindValue(':categoria_id', $categoria_id, $categoria_id === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
    $stmt->bindValue(':nome', $nome, PDO::PARAM_STR);
    $stmt->bindValue(':descricao', $descricao !== '' ? $descricao : null, $descricao !== '' ? PDO::PARAM_STR : PDO::PARAM_NULL);
    $stmt->bindValue(':preco', $preco);
    $stmt->bindValue(':preco_pj', $preco_pj, $preco_pj === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
    $stmt->bindValue(':estoque', $estoque, PDO::PARAM_INT);
    $stmt->bindValue(':imagem', $imagemBanco, $imagemBanco === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
    $stmt->bindValue(':ativo', $ativo, PDO::PARAM_INT);
    $stmt->execute();

    echo json_encode([
        'status' => true,
        'mensagem' => 'Produto atualizado com sucesso.',
    ], JSON_UNESCAPED_UNICODE);
    exit;

} catch (Throwable $e) {
    http_response_code(400);

    echo json_encode([
        'status' => false,
        'mensagem' => $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
    exit;
}