<?php
$nome = '';
$descricao = '';
$preco = '';
$estoque = 0;
$ativo = 1;
?>

<div class="d-flex justify-content-between align-items-center">
    <div>
        <h3 class="mb-1">Novo Produto</h3>
        <small class="text-muted">Cadastre um novo produto</small>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body">

        <form action="actions/admin/salvar_produto.php" method="POST" enctype="multipart/form-data">
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

                <div class="col-md-3">
                    <label for="preco" class="form-label fw-semibold">Preço (Pessoa Física)</label>
                    <input type="number" class="form-control" id="preco" name="preco" step="0.01" min="0"
                        placeholder="0,00" value="<?= htmlspecialchars($preco) ?>" required>
                </div>

                <div class="col-md-3">
                    <label for="preco_pj" class="form-label fw-semibold">Preço (Pessoa Jurídica)</label>
                    <input type="number" class="form-control" id="preco_pj" name="preco_pj" step="0.01" min="0"
                        placeholder="0,00">
                </div>

                <div class="col-md-2">
                    <label for="estoque" class="form-label fw-semibold">Estoque</label>
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

                <div class="col-12">
                    <div class="border rounded p-3 bg-light text-center" id="previewContainer" style="display:none;">
                        <img id="previewImagem" src="" alt="Preview" class="img-fluid rounded"
                            style="max-height: 240px; object-fit: contain;">
                    </div>
                </div>

                <div class="col-12 pt-2">
                    <div class="d-flex flex-column flex-md-row gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save me-2"></i>Salvar produto
                        </button>

                        <a href="admin.php?page=produtos" class="btn btn-secondary">
                            Cancelar
                        </a>
                    </div>
                </div>

            </div>
        </form>

    </div>
</div>