<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '../../../config/database.php';
require_once __DIR__ . '/produto_imagem.php';

try {
    $db = new Database();
    $pdo = $db->getConnection();

    if (!$pdo) {
        throw new Exception('Falha na conexão com o banco de dados.');
    }

    $nome = isset($_POST['nome']) ? trim((string) $_POST['nome']) : '';
    $categoria_id = isset($_POST['categoria_id']) && $_POST['categoria_id'] !== ''
        ? (int) $_POST['categoria_id']
        : null;
    $descricao = isset($_POST['descricao']) ? trim((string) $_POST['descricao']) : '';
    $preco = isset($_POST['preco']) ? (float) $_POST['preco'] : 0;
    $preco_pj = isset($_POST['preco_pj']) && $_POST['preco_pj'] !== ''
        ? (float) $_POST['preco_pj']
        : null;
    $estoque = isset($_POST['estoque']) ? (int) $_POST['estoque'] : 0;
    $ativo = isset($_POST['ativo']) ? (int) $_POST['ativo'] : 1;
    $imagemUrl = isset($_POST['imagem_url']) ? trim((string) $_POST['imagem_url']) : '';

    if ($nome === '') {
        http_response_code(400);
        echo json_encode([
            'status' => false,
            'mensagem' => 'Informe o nome do produto.'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    if ($categoria_id !== null) {
        $stmtCategoria = $pdo->prepare("
            SELECT id
            FROM categorias
            WHERE id = :id
            LIMIT 1
        ");
        $stmtCategoria->execute([
            ':id' => $categoria_id
        ]);

        if (!$stmtCategoria->fetch(PDO::FETCH_ASSOC)) {
            http_response_code(400);
            echo json_encode([
                'status' => false,
                'mensagem' => 'Categoria inválida.'
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }
    }

    if ($preco < 0) {
        http_response_code(400);
        echo json_encode([
            'status' => false,
            'mensagem' => 'Informe um preço válido.'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    if ($preco_pj !== null && $preco_pj < 0) {
        http_response_code(400);
        echo json_encode([
            'status' => false,
            'mensagem' => 'Informe um preço PJ válido.'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    if ($estoque < 0) {
        http_response_code(400);
        echo json_encode([
            'status' => false,
            'mensagem' => 'Informe um estoque válido.'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    if (!in_array($ativo, [0, 1], true)) {
        $ativo = 1;
    }

    $imagemBanco = null;

    $diretorioFisico = __DIR__ . '/../../uploads/produtos/';
    $diretorioBanco = 'uploads/produtos/';

    if ($imagemUrl !== '') {
        $imagemBanco = baixarImagemDaUrl(
            $imagemUrl,
            $diretorioFisico,
            $diretorioBanco,
            'produto_'
        );
    }

    if (isset($_FILES['imagem']) && !empty($_FILES['imagem']['name'])) {
        if ($_FILES['imagem']['error'] !== UPLOAD_ERR_OK) {
            http_response_code(400);
            echo json_encode([
                'status' => false,
                'mensagem' => 'Erro ao enviar a imagem.'
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }

        $tmpName = $_FILES['imagem']['tmp_name'];
        $type = mime_content_type($tmpName);

        $mimesPermitidos = [
            'image/jpeg',
            'image/png',
            'image/webp',
            'image/jpg'
        ];

        if (!in_array($type, $mimesPermitidos, true)) {
            http_response_code(400);
            echo json_encode([
                'status' => false,
                'mensagem' => 'Formato de imagem inválido. Envie JPG, JPEG, PNG ou WEBP.'
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }

        $nomeOriginal = $_FILES['imagem']['name'];
        $extensao = strtolower(pathinfo($nomeOriginal, PATHINFO_EXTENSION));
        $extensoesPermitidas = ['jpg', 'jpeg', 'png', 'webp'];

        if (!in_array($extensao, $extensoesPermitidas, true)) {
            http_response_code(400);
            echo json_encode([
                'status' => false,
                'mensagem' => 'Extensão de imagem inválida. Envie JPG, JPEG, PNG ou WEBP.'
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }

        if (!is_dir($diretorioFisico) && !mkdir($diretorioFisico, 0775, true)) {
            throw new Exception('Não foi possível criar a pasta de upload.');
        }

        $nomeArquivo = uniqid('produto_', true) . '.' . $extensao;
        $caminhoFisico = $diretorioFisico . $nomeArquivo;
        $imagemBanco = $diretorioBanco . $nomeArquivo;

        if (!move_uploaded_file($tmpName, $caminhoFisico)) {
            http_response_code(400);
            echo json_encode([
                'status' => false,
                'mensagem' => 'Não foi possível salvar a imagem do produto.'
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }
    }

    $stmt = $pdo->prepare("
        INSERT INTO produtos (
            categoria_id,
            nome,
            descricao,
            preco,
            preco_pj,
            estoque,
            imagem,
            ativo,
            criado_em,
            atualizado_em
        ) VALUES (
            :categoria_id,
            :nome,
            :descricao,
            :preco,
            :preco_pj,
            :estoque,
            :imagem,
            :ativo,
            NOW(),
            NULL
        )
    ");

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
        'mensagem' => 'Produto salvo com sucesso.',
        'id' => $pdo->lastInsertId()
    ], JSON_UNESCAPED_UNICODE);
    exit;

} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode([
        'status' => false,
        'mensagem' => 'Erro interno: ' . $e->getMessage()
    ], JSON_UNESCAPED_UNICODE);
    exit;
}