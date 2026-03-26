<div class="card border-0 mb-3">
    <div class="card-body p-2">
        <label for="inputPesquisarProduto" data-contexto="usuario" class="form-label fw-semibold mb-2">
            Pesquisar produtos
        </label>

        <div class="position-relative">
            <i class="fa fa-search position-absolute top-50 translate-middle-y text-muted" style="left: 14px;"></i>

            <input type="text" id="inputPesquisarProduto" class="form-control ps-5"
                placeholder="Digite para pesquisar os produtos.">
        </div>
    </div>
</div>

<div id="resultadoProdutos">
    <?php include 'includes/geral/produtos.php'; ?>
</div>