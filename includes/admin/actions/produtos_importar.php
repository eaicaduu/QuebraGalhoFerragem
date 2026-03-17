<?php
session_start();

require_once __DIR__ . '/../../../../app/config/database.php';

$produtos = $_SESSION['import_preview_produtos'] ?? [];

if (empty($produtos)) {
    $_SESSION['import_erro'] = 'Nenhum produto em pré-visualização para importar.';
    header('Location: ../../../admin.php?page=importar&acao=importar');
    exit;
}

$db = new Database();
$pdo = $db->getConnection();

try {
    $pdo->beginTransaction();

    $sql = "
        INSERT INTO produtos (
            codigo,
            codigo_barras,
            nome,
            descricao,
            grupo,
            preco,
            custo_compra,
            ultima_compra,
            estoque,
            codigo_fornecedor,
            fornecedor,
            ativo
        ) VALUES (
            :codigo,
            :codigo_barras,
            :nome,
            :descricao,
            :grupo,
            :preco,
            :custo_compra,
            :ultima_compra,
            :estoque,
            :codigo_fornecedor,
            :fornecedor,
            :ativo
        )
    ";

    $stmt = $pdo->prepare($sql);

    foreach ($produtos as $produto) {
        $descricao = $produto['descricao'] ?? ($produto['nome'] ?? null);

        $stmt->execute([
            ':codigo' => !empty($produto['codigo']) ? $produto['codigo'] : null,
            ':codigo_barras' => !empty($produto['codigo_barras']) ? $produto['codigo_barras'] : null,
            ':nome' => $descricao ?: 'Produto sem nome',
            ':descricao' => $descricao,
            ':grupo' => !empty($produto['grupo']) ? $produto['grupo'] : null,
            ':preco' => isset($produto['preco']) ? (float) $produto['preco'] : 0,
            ':custo_compra' => isset($produto['custo_compra']) ? (float) $produto['custo_compra'] : 0,
            ':ultima_compra' => !empty($produto['ultima_compra']) ? $produto['ultima_compra'] : null,
            ':estoque' => isset($produto['quantidade']) ? (float) $produto['quantidade'] : 0,
            ':codigo_fornecedor' => !empty($produto['codigo_fornecedor']) ? $produto['codigo_fornecedor'] : null,
            ':fornecedor' => !empty($produto['fornecedor']) ? $produto['fornecedor'] : null,
            ':ativo' => 1,
        ]);
    }

    $pdo->commit();

    unset(
        $_SESSION['import_headers'],
        $_SESSION['import_rows'],
        $_SESSION['import_preview_produtos'],
        $_SESSION['import_arquivo_nome'],
        $_SESSION['import_erro']
    );

    header('Location: ../../../admin.php?page=produtos&acao=todos');
    exit;

} catch (Throwable $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    $_SESSION['import_erro'] = 'Erro ao importar produtos: ' . $e->getMessage();
    header('Location: ../../../admin.php?page=importar&acao=importar');
    exit;
}