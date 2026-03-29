<?php
require_once __DIR__ . '/../../../app/models/geral/listar.php';

$pesquisa = $pesquisa ?? ($_GET['pesquisa'] ?? null);
$produtos = listar('produtos', $pesquisa, false, 'id DESC', ['nome']);
?>

<div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-2 mb-4">

    <div>
        <h3 class="mb-1">Produtos</h3>
        <small class="text-muted text-nowrap">Gerencie os produtos</small>
    </div>

    <div class="w-100 w-lg-auto text-end d-flex justify-content-end gap-2">

        <a href="admin.php?page=produto&acao=novo produto" class="btn btn-dark btn-sm">
            <i class="fa fa-plus me-1"></i>Novo
        </a>

        <button id="btnEditarProduto" class="btn btn-dark btn-sm" disabled>
            <i class="fa fa-edit"></i> Alterar
        </button>

        <button id="btnExcluirProduto" class="btn btn-danger btn-sm" disabled>
            <i class="fa fa-trash"></i> Excluir
        </button>

    </div>

</div>

<div class="d-flex flex-column gap-3">

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