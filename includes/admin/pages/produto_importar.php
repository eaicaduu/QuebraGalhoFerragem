<?php
$previewProdutos = $_SESSION['import_preview_produtos'] ?? [];
$previewArquivo = $_SESSION['import_preview_arquivo'] ?? '';
$previewErro = $_SESSION['import_preview_erro'] ?? '';

unset($_SESSION['import_preview_erro']);
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1">Importar Produtos</h3>
        <small class="text-muted">Envie um arquivo TXT do estoque para visualizar antes de importar</small>
    </div>
</div>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-body p-4">

        <?php if (!empty($previewErro)): ?>
            <div class="alert alert-danger mb-3">
                <?= htmlspecialchars($previewErro) ?>
            </div>
        <?php endif; ?>

        <form action="actions/admin/produtos_preview_importacao.php" method="POST" enctype="multipart/form-data">
            <div class="row g-3">

                <div class="col-12">
                    <label for="arquivo_importacao" class="form-label fw-semibold">Arquivo de importação</label>
                    <input type="file" class="form-control" id="arquivo_importacao" name="arquivo_importacao"
                        accept=".txt,.csv" required>
                    <div class="form-text">
                        Formatos aceitos: TXT e CSV.
                    </div>
                </div>

                <div class="col-12">
                    <div class="card border bg-light">
                        <div class="card-body py-3">
                            <div class="fw-semibold mb-2">Formato esperado</div>
                            <div class="small text-muted">
                                Relatório com colunas como: Código, Código Barras, Descrição, Grupo, Preço, Custo,
                                Últ.Compra, Quantidade, Código Fornecedor e Fornecedor.
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa-regular fa-eye me-2"></i>Visualizar
                    </button>
                </div>

            </div>
        </form>

    </div>
</div>

<?php if (!empty($previewProdutos)): ?>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-1">Pré-visualização</h4>
            <small class="text-muted">
                <?= count($previewProdutos) ?> produto(s) encontrado(s)
                <?php if (!empty($previewArquivo)): ?>
                    em
                    <?= htmlspecialchars($previewArquivo) ?>
                <?php endif; ?>
            </small>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-3 p-md-4">

            <div class="d-flex flex-column gap-3">
                <?php foreach ($previewProdutos as $produto): ?>
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="row g-3 align-items-center">

                                <div class="col-12 col-md-3">
                                    <div class="fw-bold">
                                        <?= htmlspecialchars($produto['descricao']) ?>
                                    </div>
                                    <div class="small text-muted">
                                        Código:
                                        <?= htmlspecialchars($produto['codigo']) ?>
                                    </div>
                                    <div class="small text-muted">
                                        Barras:
                                        <?= htmlspecialchars($produto['codigo_barras']) ?>
                                    </div>
                                </div>

                                <div class="col-6 col-md-2">
                                    <div class="small text-muted">Grupo</div>
                                    <div>
                                        <?= htmlspecialchars($produto['grupo']) ?>
                                    </div>
                                </div>

                                <div class="col-6 col-md-2">
                                    <div class="small text-muted">Preço</div>
                                    <div class="fw-semibold text-primary">
                                        R$
                                        <?= number_format((float) $produto['preco'], 2, ',', '.') ?>
                                    </div>
                                </div>

                                <div class="col-6 col-md-2">
                                    <div class="small text-muted">Custo</div>
                                    <div>
                                        R$
                                        <?= number_format((float) $produto['custo_compra'], 2, ',', '.') ?>
                                    </div>
                                </div>

                                <div class="col-6 col-md-1">
                                    <div class="small text-muted">Qtd</div>
                                    <div>
                                        <?= number_format((float) $produto['quantidade'], 4, ',', '.') ?>
                                    </div>
                                </div>

                                <div class="col-12 col-md-2">
                                    <div class="small text-muted">Fornecedor</div>
                                    <div>
                                        <?= htmlspecialchars($produto['fornecedor']) ?>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <form action="actions/admin/produtos_importar.php" method="POST" class="mt-4">
                <div class="d-flex flex-column flex-md-row gap-2">
                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-check me-2"></i>Confirmar importação
                    </button>

                    <a href="actions/admin/produtos_limpar_preview.php" class="btn btn-outline-secondary">
                        Cancelar pré-visualização
                    </a>
                </div>
            </form>

        </div>
    </div>

<?php endif; ?>