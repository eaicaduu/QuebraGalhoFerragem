<?php
$rows = $_SESSION['import_rows'] ?? [];
?>

<div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-2 mb-4">

    <div>
        <div class="d-flex align-items-center">
            <h3>Firebird</h3>
            <?php if (!empty($rows)): ?>
                <span class="text-muted small ms-1">
                    (<?= count($rows) ?>)
                </span>
            <?php endif; ?>
        </div>
        <small class="text-muted text-nowrap">Visualize os produtos importados</small>
    </div>

    <div class="w-100 w-lg-auto text-end d-flex justify-content-end gap-2 flex-wrap">

        <a id="btnImportarFirebird" class="btn btn-dark btn-sm">
            <i class="fa fa-sync me-1"></i>Atualizar
        </a>

        <button id="btnFiltroFirebird" class="btn btn-dark btn-sm">
            <i class="fa fa-filter"></i> Filtro
        </button>

        <a href="admin.php?page=produto&acao=todos produtos" class="btn btn-secondary btn-sm">
            <i class="fa fa-arrow-left me-1"></i>Voltar
        </a>
    </div>

</div>

<div class="d-flex flex-column gap-3">

    <div class="shadow-sm rounded overflow-auto" style="max-height: 50vh;">

        <div class="card-body p-3">

            <div class="position-sticky top-0 z-3 bg-white pt-2 pb-2">
                <div class="position-relative">
                    <i class="fa fa-search position-absolute top-50 translate-middle-y text-muted"
                        style="left: 14px;"></i>

                    <input type="text" id="inputPesquisarFirebird" data-contexto="admin" class="form-control ps-5"
                        placeholder="Digite para pesquisar os produtos.">
                </div>
            </div>

            <?php if (empty($rows)): ?>

                <div class="col-12">
                    <div class="alert bg-body-secondary text-center mb-0">
                        Nenhum produto encontrado.
                    </div>
                </div>

            <?php else: ?>

                <?php foreach ($rows as $row): ?>
                    <?php
                    $codigo = trim((string) ($row['CODIGO'] ?? ''));
                    $referencia = trim((string) ($row['REFERENCIA'] ?? ''));
                    $descricao = trim((string) ($row['DESCRICAO'] ?? ''));
                    $nome = trim((string) ($row['NOME'] ?? ''));
                    $fornecedor = trim((string) ($row['FORNECEDOR'] ?? ''));
                    $medida = trim((string) ($row['MEDIDA'] ?? ''));
                    $preco = (float) ($row['PRECO'] ?? 0);
                    $custoCompra = (float) ($row['CUSTOCOMPR'] ?? 0);
                    $estoqueAtual = (float) ($row['QTD_ATUAL'] ?? 0);
                    $estoqueMinimo = (float) ($row['QTD_MINIM'] ?? 0);
                    $ativo = (int) ($row['ATIVO'] ?? 0);
                    ?>

                    <label class="card border-0 bg-body-secondary mb-2 item-produto-firebird" style="cursor:pointer;">
                        <div class="card-body py-2 px-3">

                            <div class="d-flex align-items-center gap-2">

                                <input
                                    type="radio"
                                    name="produtoFirebirdSelecionado"
                                    class="form-check-input mt-1 flex-shrink-0"
                                    value="<?= htmlspecialchars($codigo) ?>"
                                    data-codigo="<?= htmlspecialchars($codigo) ?>"
                                    data-descricao="<?= htmlspecialchars($descricao) ?>"
                                    data-referencia="<?= htmlspecialchars($referencia) ?>"
                                    data-nome="<?= htmlspecialchars($nome) ?>"
                                    data-fornecedor="<?= htmlspecialchars($fornecedor) ?>"
                                    data-medida="<?= htmlspecialchars($medida) ?>"
                                    data-preco="<?= htmlspecialchars($preco) ?>"
                                    data-custo="<?= htmlspecialchars($custoCompra) ?>"
                                    data-estoque="<?= htmlspecialchars($estoqueAtual) ?>"
                                    data-estoque-minimo="<?= htmlspecialchars($estoqueMinimo) ?>"
                                    data-ativo="<?= htmlspecialchars($ativo) ?>">

                                <div class="flex-grow-1 overflow-hidden" style="min-width:0;">

                                    <div class="d-flex justify-content-between align-items-start gap-2">
                                        <div class="fw-semibold text-truncate">
                                            <?= htmlspecialchars($codigo ?: '-') ?> -
                                            <?= htmlspecialchars($descricao ?: '-') ?>
                                        </div>

                                        <div class="text-nowrap fw-semibold ms-2">
                                            R$ <?= number_format($preco, 2, ',', '.') ?>
                                        </div>
                                    </div>

                                    <div class="d-flex flex-wrap justify-content-between align-items-start gap-2">
                                        <div class="fw-semibold text-truncate">
                                            <span class="badge <?= $ativo ? 'bg-success' : 'bg-danger' ?>">
                                                <i class="fa fa-circle me-1"></i>
                                                <?= $ativo ? 'Ativo' : 'Inativo' ?>
                                            </span>
                                            <span class="badge <?= $referencia && $referencia !== 'SEM GTIN' ? 'bg-dark' : 'bg-danger' ?>">
                                                <?= htmlspecialchars($referencia ?: 'SEM GTIN') ?>
                                            </span>
                                            <span class="badge bg-dark">
                                                <?= htmlspecialchars($medida ?: 'UN') ?>
                                            </span>
                                        </div>

                                        <div class="text-nowrap small text-muted fw-semibold ms-2">
                                            R$ <?= htmlspecialchars($custoCompra ?: '0,00') ?>
                                        </div>
                                    </div>
                                    <div class="small text-muted text-truncate">
                                        <?= htmlspecialchars($fornecedor ?: 'Sem fornecedor') ?>
                                    </div>

                                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mt-1">

                                        <div class="small text-muted text-truncate">
                                            <?= htmlspecialchars($nome ?: 'Sem categoria') ?>
                                        </div>

                                        <div class="d-flex gap-1 flex-wrap">

                                            <!-- Estoque atual -->
                                            <span class="badge <?= $estoqueAtual > 0 ? 'bg-primary' : 'bg-secondary' ?>">
                                                <i class="fa fa-box me-1"></i>
                                                <?= $estoqueAtual > 0
                                                    ? number_format($estoqueAtual, 2, ',', '.')
                                                    : '0' ?>
                                            </span>

                                            <!-- Estoque mínimo -->
                                            <span class="badge <?= ($estoqueMinimo > 0 && $estoqueAtual <= $estoqueMinimo)
                                                                    ? 'bg-warning text-dark'
                                                                    : 'bg-dark' ?>">

                                                <?= ($estoqueMinimo > 0 && $estoqueAtual <= $estoqueMinimo) ? '<i class="fa fa-exclamation-triangle me-1"></i>' : '' ?>

                                                <?= $estoqueMinimo > 0
                                                    ? 'Mínimo: ' . number_format($estoqueMinimo, 2, ',', '.')
                                                    : 'Mínimo: 0,00' ?>
                                            </span>

                                        </div>

                                    </div>

                                </div>

                            </div>

                        </div>
                    </label>

                <?php endforeach; ?>

            <?php endif; ?>

        </div>

    </div>

</div>