<?php
require_once __DIR__ . '/../../../app/models/geral/listar.php';

$categorias = listar('categorias', null, false, 'id DESC', ['nome']);

$acao = $_GET['acao'] ?? 'novo produto';
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$modoEdicao = $acao === 'editar produto' && $id > 0;

$categoria_id = '';
$nome = '';
$codigo = '';
$referencia = '';
$descricao = '';
$fornecedor = '';
$medida = '';
$preco = '';
$preco_pj = '';
$custo_compra = '';
$estoque = 0;
$estoque_minimo = 0;
$ativo = 1;
$imagem = '';

if ($modoEdicao) {
    require_once __DIR__ . '/../../../app/models/geral/buscar.php';

    $produto = buscar('produtos', $id);

    $categoria_id = $produto['categoria_id'] ?? '';
    $nome = $produto['nome'] ?? '';
    $codigo = $produto['codigo'] ?? '';
    $referencia = $produto['referencia'] ?? '';
    $descricao = $produto['descricao'] ?? '';
    $fornecedor = $produto['fornecedor'] ?? '';
    $medida = $produto['medida'] ?? '';
    $preco = $produto['preco'] ?? '';
    $preco_pj = $produto['preco_pj'] ?? '';
    $custo_compra = $produto['custo_compra'] ?? '';
    $estoque = $produto['estoque'] ?? 0;
    $estoque_minimo = $produto['estoque_minimo'] ?? 0;
    $ativo = $produto['ativo'] ?? 1;
    $imagem = $produto['imagem'] ?? '';
}

$formId = $modoEdicao ? 'formEditarProduto' : 'formNovoProduto';
$btnId = $modoEdicao ? 'btnEditarProduto' : 'btnSalvarProduto';
?>

<div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-2 mb-4">

    <div>
        <h3 class="mb-1 text-nowrap"><?= $modoEdicao ? 'Editar Produto' : 'Novo Produto' ?></h3>
        <small class="text-muted text-nowrap">
            <?= $modoEdicao ? 'Atualize os dados do produto' : 'Cadastre um novo produto' ?>
        </small>
    </div>

    <div class="w-100 w-lg-auto text-end d-flex justify-content-end gap-2">

        <button type="submit" form="<?= $formId ?>" id="<?= $btnId ?>" class="btn btn-success btn-sm">
            <i class="fa fa-save me-2"></i>
            <?= $modoEdicao ? 'Atualizar' : 'Salvar' ?>
        </button>

        <?php if ($modoEdicao): ?>
            <button type="button"
                class="btn <?= empty($imagem) ? 'btn-secondary' : 'btn-danger' ?> btn-sm"
                id="btnRemoverImagem"
                <?= empty($imagem) ? 'disabled' : '' ?>>
                <i class="fa fa-trash me-1"></i> Remover imagem
            </button>
        <?php endif; ?>

        <a href="admin.php?page=produto&acao=todos produtos" class="btn btn-secondary btn-sm">
            <i class="fa fa-arrow-left me-1"></i>Voltar
        </a>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">

        <form id="<?= $formId ?>" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="modo" value="<?= $modoEdicao ? 'editar' : 'novo' ?>">
            <input type="hidden" name="id" value="<?= $modoEdicao ? (int) $id : '' ?>">
            <input type="hidden" name="imagem_atual" value="<?= htmlspecialchars($imagem) ?>">

            <div class="row g-3">

                <div class="col-12">
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

                    <?php $imagemAtual = !empty($imagem) ? 'app/' . $imagem : 'images/default.png'; ?>

                    <div class="border rounded p-3 text-center pe-none" id="previewContainer">
                        <img id="previewImagem" src="<?= htmlspecialchars($imagemAtual, ENT_QUOTES, 'UTF-8') ?>"
                            alt="Preview" class="img-fluid" style="max-height:240px; object-fit:contain;">
                    </div>

                    <input type="hidden" name="remover_imagem" id="remover_imagem" value="0">
                </div>

                <div class="col-md-6">
                    <label for="nome" class="form-label fw-semibold">Nome do produto</label>
                    <input type="text" class="form-control" id="nome" name="nome"
                        placeholder="Digite o nome do produto"
                        value="<?= htmlspecialchars($nome) ?>" required>
                </div>

                <div class="col-md-3">
                    <label for="codigo" class="form-label fw-semibold">Código</label>
                    <input type="text" class="form-control" id="codigo" name="codigo"
                        placeholder="Código interno"
                        value="<?= htmlspecialchars($codigo) ?>">
                </div>

                <div class="col-md-3">
                    <label for="referencia" class="form-label fw-semibold">Referência / GTIN</label>
                    <input type="text" class="form-control" id="referencia" name="referencia"
                        placeholder="GTIN ou referência"
                        value="<?= htmlspecialchars($referencia) ?>">
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Categoria</label>

                    <div class="position-relative">
                        <input type="text" id="inputCategoria" class="form-control"
                            placeholder="Selecione ou pesquise uma categoria"
                            value="">

                        <input type="hidden" name="categoria_id" id="categoria_id"
                            value="<?= htmlspecialchars((string) $categoria_id) ?>">

                        <div id="listaCategorias" class="list-group position-absolute w-100 shadow-sm"
                            style="z-index: 1000; display:none; max-height:200px; overflow:auto;">

                            <?php foreach ($categorias as $cat): ?>
                                <button type="button" class="list-group-item list-group-item-action item-categoria-select"
                                    data-id="<?= (int) $cat['id'] ?>"
                                    data-nome="<?= htmlspecialchars($cat['nome']) ?>">
                                    <?= htmlspecialchars($cat['nome']) ?>
                                </button>
                            <?php endforeach; ?>

                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <label for="fornecedor" class="form-label fw-semibold">Fornecedor</label>
                    <input type="text" class="form-control" id="fornecedor" name="fornecedor"
                        placeholder="Fornecedor"
                        value="<?= htmlspecialchars($fornecedor) ?>">
                </div>

                <div class="col-md-3">
                    <label for="medida" class="form-label fw-semibold">Medida</label>
                    <input type="text" class="form-control" id="medida" name="medida"
                        placeholder="Ex.: UN, CX, MT"
                        value="<?= htmlspecialchars($medida) ?>">
                </div>

                <div class="col-12">
                    <label for="descricao" class="form-label fw-semibold">Descrição</label>
                    <textarea class="form-control" id="descricao" name="descricao" rows="4"
                        placeholder="Descreva o produto"><?= htmlspecialchars($descricao) ?></textarea>
                </div>

                <div class="col-md-2">
                    <label for="preco" class="form-label fw-semibold">Preço CPF</label>
                    <input type="number" class="form-control" id="preco" name="preco" step="0.01" min="0"
                        placeholder="0,00" value="<?= htmlspecialchars((string) $preco) ?>" required>
                </div>

                <div class="col-md-2">
                    <label for="preco_pj" class="form-label fw-semibold">Preço PJ</label>
                    <input type="number" class="form-control" id="preco_pj" name="preco_pj" step="0.01" min="0"
                        placeholder="0,00" value="<?= htmlspecialchars((string) $preco_pj) ?>">
                </div>

                <div class="col-md-2">
                    <label for="custo_compra" class="form-label fw-semibold">Custo</label>
                    <input type="number" class="form-control" id="custo_compra" name="custo_compra" step="0.01" min="0"
                        placeholder="0,00" value="<?= htmlspecialchars((string) $custo_compra) ?>">
                </div>

                <div class="col-md-2">
                    <label for="estoque" class="form-label fw-semibold">Estoque</label>
                    <input type="number" class="form-control" id="estoque" name="estoque" step="0.001" min="0"
                        placeholder="0" value="<?= htmlspecialchars((string) $estoque) ?>" required>
                </div>

                <div class="col-md-2">
                    <label for="estoque_minimo" class="form-label fw-semibold">Mínimo</label>
                    <input type="number" class="form-control" id="estoque_minimo" name="estoque_minimo" step="0.001" min="0"
                        placeholder="0" value="<?= htmlspecialchars((string) $estoque_minimo) ?>">
                </div>

                <div class="col-md-2">
                    <label for="ativo" class="form-label fw-semibold">Status</label>
                    <select class="form-select" id="ativo" name="ativo" required>
                        <option value="1" <?= $ativo == 1 ? 'selected' : '' ?>>Ativo</option>
                        <option value="0" <?= $ativo == 0 ? 'selected' : '' ?>>Inativo</option>
                    </select>
                </div>

            </div>
        </form>

    </div>
</div>