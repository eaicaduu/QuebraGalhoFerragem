<?php
require_once __DIR__ . '/../../../app/models/geral/listar.php';

$categorias = listar('categorias', null, false, 'id DESC', ['nome']);

$acao = $_GET['acao'] ?? 'novo produto';
$id = isset($_GET['id']) ? (int) $id = (int) $_GET['id'] : 0;
$modoEdicao = $acao === 'editar produto' && $id > 0;

$categoria_id = '';
$nome = '';
$descricao = '';
$preco = '';
$preco_pj = '';
$estoque = 0;
$ativo = 1;
$imagem = '';

if ($modoEdicao) {
    require_once __DIR__ . '/../../../app/models/geral/buscar.php';

    $produto = buscar('produtos', $id);

    $categoria_id = $produto['categoria_id'] ?? '';
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

<div class="d-flex justify-content-between align-items-center mb-4">
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

                <div class="col-12 mb-3">
                    <label class="form-label fw-semibold">Imagem do produto</label>

                    <div id="erroImagem" class="text-danger small mt-1" style="display:none;">
                        URL inválida ou imagem não encontrada.
                    </div>

                    <input type="file" class="form-control mb-2" id="imagem" name="imagem"
                        accept=".jpg,.jpeg,.png,.webp">

                    <input type="text" class="form-control mb-2" id="imagem_url" name="imagem_url"
                        placeholder="Ou cole a URL da imagem (https://...)">

                    <div class="form-text mb-2">
                        Formatos permitidos: JPG, JPEG, PNG e WEBP.
                    </div>

                    <?php
                    $imagemAtual = !empty($imagem) ? 'app/' . $imagem : 'images/produto.png';
                    ?>

                    <div class="border rounded p-3 text-center pe-none" id="previewContainer">
                        <img id="previewImagem" src="<?= htmlspecialchars($imagemAtual, ENT_QUOTES, 'UTF-8') ?>"
                            alt="Preview" class="img-fluid" style="max-height:240px; object-fit:contain;">
                    </div>

                    <input type="hidden" name="remover_imagem" id="remover_imagem" value="0">
                </div>

                <div class="col-12 mb-3">
                    <label for="nome" class="form-label fw-semibold">Nome do produto</label>
                    <input type="text" class="form-control" id="nome" name="nome" placeholder="Digite o nome do produto"
                        value="<?= htmlspecialchars($nome) ?>" required>
                </div>

                <div class="col-12 mb-3">
                    <label class="form-label fw-semibold">Categoria</label>

                    <div class="position-relative">

                        <input type="text" id="inputCategoria" class="form-control"
                            placeholder="Selecione ou pesquise uma categoria">

                        <input type="hidden" name="categoria_id" id="categoria_id">

                        <div id="listaCategorias" class="list-group position-absolute w-100 shadow-sm"
                            style="z-index: 1000; display:none; max-height:200px; overflow:auto;">

                            <?php foreach ($categorias as $cat): ?>
                                <button type="button" class="list-group-item list-group-item-action item-categoria-select"
                                    data-id="<?= (int) $cat['id'] ?>" data-nome="<?= htmlspecialchars($cat['nome']) ?>">
                                    <?= htmlspecialchars($cat['nome']) ?>
                                </button>
                            <?php endforeach; ?>

                        </div>

                    </div>
                </div>

                <div class="col-12 mb-3">
                    <label for="descricao" class="form-label fw-semibold">Descrição</label>
                    <textarea class="form-control" id="descricao" name="descricao" rows="5"
                        placeholder="Descreva o produto"><?= htmlspecialchars($descricao) ?></textarea>
                </div>

                <div class="col-3">
                    <label for="preco" class="form-label fw-semibold">Preço CPF</label>
                    <input type="number" class="form-control" id="preco" name="preco" step="0.01" min="0"
                        placeholder="0,00" value="<?= htmlspecialchars((string) $preco) ?>" required>
                </div>

                <div class="col-3">
                    <label for="preco_pj" class="form-label fw-semibold">Preço PJ</label>
                    <input type="number" class="form-control" id="preco_pj" name="preco_pj" step="0.01" min="0"
                        placeholder="0,00" value="<?= htmlspecialchars((string) $preco_pj) ?>">
                </div>

                <div class="col-3">
                    <label for="estoque" class="form-label fw-semibold">Estoque</label>
                    <input type="number" class="form-control" id="estoque" name="estoque" min="0" placeholder="0"
                        value="<?= htmlspecialchars((string) $estoque) ?>" required>
                </div>

                <div class="col-3">
                    <label for="ativo" class="form-label fw-semibold">Status</label>
                    <select class="form-select" id="ativo" name="ativo" required>
                        <option value="1" <?= $ativo == 1 ? 'selected' : '' ?>>Ativo</option>
                        <option value="0" <?= $ativo == 0 ? 'selected' : '' ?>>Inativo</option>
                    </select>
                </div>

                <div class="col-12 mt-4">
                    <div class="d-flex flex-column flex-md-row gap-2">

                        <button type="submit" id="<?= $btnId ?>" class="btn btn-success btn-sm">
                            <i class="fa fa-save me-2"></i>
                            <?= $modoEdicao ? 'Atualizar produto' : 'Salvar produto' ?>
                        </button>

                        <?php if ($modoEdicao): ?>
                            <button type="button" class="btn <?= empty($imagem) ? 'btn-secondary' : 'btn-danger' ?> btn-sm"
                                id="btnRemoverImagem" <?= empty($imagem) ? 'disabled' : '' ?>>
                                <i class="fa fa-trash me-1"></i> Remover imagem
                            </button>
                        <?php endif; ?>

                        <a href="admin.php?page=produto&acao=todos produtos" class="btn btn-secondary btn-sm">
                            <i class="fa fa-times me-2"></i>
                            Cancelar
                        </a>

                    </div>
                </div>

            </div>
        </form>

    </div>
</div>