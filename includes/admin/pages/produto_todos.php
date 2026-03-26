<?php
require_once __DIR__ . '/../../../app/models/geral/produto_listar.php';

$pesquisa = $pesquisa ?? ($_GET['pesquisa'] ?? null);
$produtos = listarProdutos($pesquisa ?? '', 'admin');
?>

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h3 class="mb-1">Produtos</h3>
        <small class="text-muted">Gerencie os produtos da loja</small>
    </div>

</div>

<div class="d-flex flex-column gap-3">
    <div class="card border-0">

        <div class="shadow-sm rounded overflow-auto" style="max-height: 50vh;">

            <div class="card-body p-3">

                <div class="position-sticky top-0 z-3 bg-white pt-2 pb-2">
                    <div class="position-relative">
                        <i class="fa fa-search position-absolute top-50 translate-middle-y text-muted"
                            style="left: 14px;"></i>

                        <input type="text" id="inputPesquisarProduto" data-contexto="admin" class="form-control ps-5"
                            placeholder="Digite para pesquisar os produtos.">
                    </div>
                </div>

                <div id="resultadoProdutos">
                    <?php include 'produto_resultado.php'; ?>
                </div>

            </div>

        </div>

    </div>
</div>