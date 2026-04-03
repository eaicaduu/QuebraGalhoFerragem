<?php
require_once __DIR__ . '/../../../app/models/geral/listar.php';

$categorias = listar(
    'categorias c',
    null,
    false,
    'c.id DESC',
    ['c.nome'],
    'c.*, COUNT(p.id) AS total_produtos',
    'LEFT JOIN produtos p ON p.categoria_id = c.id',
    'c.id, c.nome, c.ativo'
);
?>

<div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-2 mb-2">

    <div>
        <h3 class="mb-1">Categorias</h3>
        <small class="text-muted text-nowrap">Gerencie as categorias</small>
    </div>

    <div class="w-100 w-lg-auto text-end d-flex justify-content-end gap-2">

        <a href="admin.php?page=categoria&acao=nova categoria" class="btn btn-dark btn-sm">
            <i class="fa fa-plus me-1"></i>Novo
        </a>

        <button id="btnEditarCategoria" class="btn btn-dark btn-sm" disabled>
            <i class="fa fa-edit"></i> Alterar
        </button>

        <button id="btnExcluirCategoria" class="btn btn-danger btn-sm" disabled>
            <i class="fa fa-trash"></i> Excluir
        </button>

    </div>

</div>

<div class="d-flex flex-column gap-3">

    <div class="shadow-sm rounded overflow-auto" style="max-height: 50vh;">

        <div class="card-body p-3">

            <div class="position-sticky top-0 z-3 bg-white pt-2 pb-2">
                <div class="d-flex align-items-center gap-2">

                    <div class="position-relative flex-grow-1">
                        <i class="fa fa-search position-absolute top-50 translate-middle-y text-muted"
                            style="left: 14px;"></i>

                        <input type="text"
                            id="inputPesquisarCategoria"
                            data-contexto="admin"
                            class="form-control ps-5 form-control-sm"
                            placeholder="Pesquisar categorias.">
                    </div>

                    <button id="btnFiltroCategoria" class="btn btn-dark btn-sm">
                        <i class="fa fa-filter"></i>
                    </button>

                </div>
            </div>

            <?php if (empty($categorias)): ?>

                <div class="alert bg-body-secondary text-center">
                    Nenhuma categoria encontrada.
                </div>

            <?php else: ?>

                <?php foreach ($categorias as $cat): ?>

                    <div class="card border-0 bg-body-secondary mb-2 item-categoria" style="cursor: pointer">
                        <div class="card-body py-2 px-3">

                            <div class="d-flex align-items-center gap-3">

                                <input type="radio" name="categoriaSelecionada" class="form-check-input radio-categoria"
                                    value="<?= (int) $cat['id'] ?>">

                                <div>
                                    <div class="fw-semibold">
                                        <?= (int) $cat['id'] ?> - <?= htmlspecialchars($cat['nome']) ?>
                                    </div>

                                    <span class="badge <?= $cat['ativo'] ? 'bg-success' : 'bg-danger' ?>">
                                        <?= $cat['ativo'] ? 'Ativo' : 'Inativo' ?>
                                    </span>

                                    <?php $total = (int) ($cat['total_produtos'] ?? 0); ?>

                                    <span class="badge bg-dark">
                                        <?= $total ?> produto<?= $total == 1 ? '' : 's' ?>
                                    </span>
                                </div>

                            </div>

                        </div>
                    </div>

                <?php endforeach; ?>

            <?php endif; ?>

        </div>
    </div>
</div>