<?php

$acao = $_GET['acao'] ?? 'novo';
$id = isset($_GET['id']) ? (int) $id = (int) $_GET['id'] : 0;
$modoEdicao = $acao === 'editar' && $id > 0;

$nome = '';
$descricao = '';
$preco = '';
$preco_pj = '';
$estoque = 0;
$ativo = 1;
$imagem = '';

if ($modoEdicao) {
    require_once __DIR__ . '/../../../app/models/produto/produto_buscar.php';

    $produto = buscarProduto($id);

    $nome = $produto['nome'] ?? '';
    $descricao = $produto['descricao'] ?? '';
    $preco = $produto['preco'] ?? '';
    $preco_pj = $produto['preco_pj'] ?? '';
    $estoque = $produto['estoque'] ?? 0;
    $ativo = $produto['ativo'] ?? 1;
    $imagem = $produto['imagem'] ?? '';
}

$formId = $modoEdicao ? 'formEditarProduto' : 'formNovoProduto';
$btnId = $modoEdicao ? 'btnEditarProduto' : 'btnSalvarProduto';
?>

<div class="d-flex justify-content-between align-items-center">
    <div>
        <h3 class="mb-1"><?= $modoEdicao ? 'Editar Produto' : 'Novo Produto' ?></h3>
        <small class="text-muted">
            <?= $modoEdicao ? 'Atualize os dados do produto' : 'Cadastre um novo produto' ?>
        </small>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">

        <form id="<?= $formId ?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="modo" value="<?= $modoEdicao ? 'editar' : 'novo' ?>">
            <input type="hidden" name="id" value="<?= $modoEdicao ? (int) $id : '' ?>">
            <input type="hidden" name="imagem_atual" value="<?= htmlspecialchars($imagem) ?>">

            <div class="row">

                <div class="col-10">
                    <label for="nome" class="form-label fw-semibold">Nome do produto</label>
                    <input type="text" class="form-control" id="nome" name="nome" placeholder="Digite o nome do produto"
                        value="<?= htmlspecialchars($nome) ?>" required>
                </div>

                <div class="col-md-2">
                    <label for="ativo" class="form-label fw-semibold">Status</label>
                    <select class="form-select" id="ativo" name="ativo" required>
                        <option value="1" <?= $ativo == 1 ? 'selected' : '' ?>>Ativo</option>
                        <option value="0" <?= $ativo == 0 ? 'selected' : '' ?>>Inativo</option>
                    </select>
                </div>

                <div class="col-12">
                    <label for="descricao" class="form-label fw-semibold">Descrição</label>
                    <textarea class="form-control" id="descricao" name="descricao" rows="5"
                        placeholder="Descreva o produto"><?= htmlspecialchars($descricao) ?></textarea>
                </div>

                <div class="col-md-4">
                    <label for="preco" class="form-label fw-semibold">Preço (Pessoa Física)</label>
                    <input type="number" class="form-control" id="preco" name="preco" step="0.01" min="0"
                        placeholder="0,00" value="<?= htmlspecialchars((string) $preco) ?>" required>
                </div>

                <div class="col-md-4">
                    <label for="preco_pj" class="form-label fw-semibold">Preço (Pessoa Jurídica)</label>
                    <input type="number" class="form-control" id="preco_pj" name="preco_pj" step="0.01" min="0"
                        placeholder="0,00" value="<?= htmlspecialchars((string) $preco_pj) ?>">
                </div>

                <div class="col-md-4">
                    <label for="estoque" class="form-label fw-semibold">Quantidade Estoque</label>
                    <input type="number" class="form-control" id="estoque" name="estoque" min="0" placeholder="0"
                        value="<?= htmlspecialchars((string) $estoque) ?>" required>
                </div>

                <div class="col-12">
                    <label for="imagem" class="form-label fw-semibold">Imagem do produto</label>
                    <input type="file" class="form-control" id="imagem" name="imagem" accept=".jpg,.jpeg,.png,.webp">
                    <div class="form-text">
                        Formatos permitidos: JPG, JPEG, PNG e WEBP.
                    </div>
                </div>

                <?php if ($modoEdicao && !empty($imagem)): ?>
                    <div class="col-12">
                        <div class="mt-2">
                            <small class="text-muted d-block mb-2">Imagem atual</small>
                            <img src="app/<?= htmlspecialchars($imagem) ?>" alt="Imagem atual"
                                class="img-fluid rounded border" style="max-height: 180px; object-fit: contain;">
                        </div>
                    </div>
                <?php endif; ?>

                <div class="col-12">
                    <div class="border rounded p-3 bg-light text-center" id="previewContainer" style="display:none;">
                        <img id="previewImagem" src="" alt="Preview" class="img-fluid rounded"
                            style="max-height: 240px; object-fit: contain;">
                    </div>
                </div>

                <div class="col-12 pt-2">
                    <div class="d-flex flex-column flex-md-row gap-2">

                        <button type="submit" id="<?= $btnId ?>" class="btn btn-primary">
                            <i class="fa fa-save me-2"></i>
                            <?= $modoEdicao ? 'Atualizar produto' : 'Salvar produto' ?>
                        </button>

                        <?php if ($modoEdicao): ?>
                            <a href="admin.php?page=produtos&acao=todos" class="btn btn-secondary">
                                <i class="fa fa-arrow-left me-2"></i>
                                Cancelar
                            </a>
                        <?php endif; ?>

                    </div>
                </div>

            </div>
        </form>

    </div>
</div>