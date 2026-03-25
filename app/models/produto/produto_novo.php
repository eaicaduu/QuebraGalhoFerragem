<?php
header('Content-Type: application/json');

require_once __DIR__ . '../../../config/database.php';

try {
    $db = new Database();
    $pdo = $db->getConnection();

    $nome = isset($_POST['nome']) ? trim((string) $_POST['nome']) : '';
    $descricao = isset($_POST['descricao']) ? trim((string) $_POST['descricao']) : '';
    $preco = isset($_POST['preco']) ? (float) $_POST['preco'] : 0;
    $preco_pj = isset($_POST['preco_pj']) && $_POST['preco_pj'] !== '' ? (float) $_POST['preco_pj'] : null;
    $estoque = isset($_POST['estoque']) ? (int) $_POST['estoque'] : 0;
    $ativo = isset($_POST['ativo']) ? (int) $_POST['ativo'] : 1;

    if ($nome === '') {
        http_response_code(400);
        echo json_encode([
            'status' => false,
            'mensagem' => 'Informe o nome do produto.'
        ]);
        exit;
    }

    if ($preco < 0) {
        http_response_code(400);
        echo json_encode([
            'status' => false,
            'mensagem' => 'Informe um preço válido.'
        ]);
        exit;
    }

    if ($preco_pj !== null && $preco_pj < 0) {
        http_response_code(400);
        echo json_encode([
            'status' => false,
            'mensagem' => 'Informe um preço PJ válido.'
        ]);
        exit;
    }

    if ($estoque < 0) {
        http_response_code(400);
        echo json_encode([
            'status' => false,
            'mensagem' => 'Informe um estoque válido.'
        ]);
        exit;
    }

    if (!in_array($ativo, [0, 1], true)) {
        $ativo = 1;
    }

    $imagemBanco = null;

    if (isset($_FILES['imagem']) && !empty($_FILES['imagem']['name'])) {
        if ($_FILES['imagem']['error'] !== UPLOAD_ERR_OK) {
            http_response_code(400);
            echo json_encode([
                'status' => false,
                'mensagem' => 'Erro ao enviar a imagem.'
            ]);
            exit;
        }

        $tmpName = $_FILES['imagem']['tmp_name'];
        $type = mime_content_type($tmpName);

        $permitidas = ['image/jpeg', 'image/png', 'image/webp', 'image/jpg'];

        if (!in_array($type, $permitidas, true)) {
            http_response_code(400);
            echo json_encode([
                'status' => false,
                'mensagem' => 'Formato de imagem inválido. Envie JPG, JPEG, PNG ou WEBP.'
            ]);
            exit;
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
            http_response_code(400);
            echo json_encode([
                'status' => false,
                'mensagem' => 'Não foi possível salvar a imagem do produto.'
            ]);
            exit;
        }
    }

    $stmt = $pdo->prepare("
        INSERT INTO produtos (
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

    $stmt->execute([
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
        'mensagem' => 'Produto salvo com sucesso.',
        'id' => $pdo->lastInsertId()
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