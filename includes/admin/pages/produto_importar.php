<?php
$importErro = $_SESSION['import_erro'] ?? '';
$importHeaders = $_SESSION['import_headers'] ?? [];
$importRows = $_SESSION['import_rows'] ?? [];
$importPreview = $_SESSION['import_preview_produtos'] ?? [];
$importArquivo = $_SESSION['import_arquivo_nome'] ?? '';

unset($_SESSION['import_erro']);
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1">Importar Produtos</h3>
        <small class="text-muted">Improte os produto para o sistema</small>
    </div>
</div>

<?php if (!empty($importErro)): ?>
    <div class="alert alert-danger">
        <?= htmlspecialchars($importErro) ?>
    </div>
<?php endif; ?>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-body p-4">

        <form action="includes/admin/actions/produtos_upload_importacao.php" method="POST" enctype="multipart/form-data">
            <div class="row g-3">

                <div class="col-12">
                    <label for="arquivo_importacao" class="form-label fw-semibold">Arquivo</label>
                    <input type="file" class="form-control" id="arquivo_importacao" name="arquivo_importacao"
                        accept=".pdf,.txt" required>
                    <div class="form-text">Formatos aceitos: PDF e TXT.</div>
                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="fa fa-upload me-2"></i>Enviar arquivo
                    </button>
                </div>

            </div>
        </form>

    </div>
</div>

<?php if (!empty($importHeaders) && !empty($importRows)): ?>

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-4">

            <div class="mb-3">
                <h5 class="mb-1">Mapear colunas</h5>
                <small class="text-muted">
                    Arquivo:
                    <?= htmlspecialchars($importArquivo) ?> ·
                    <?= count($importRows) ?> linha(s) detectada(s)
                </small>
            </div>

            <form action="includes/admin/actions/produtos_mapear_colunas.php" method="POST">
                <div class="row g-3">

                    <?php foreach ($importHeaders as $index => $header): ?>
                        <div class="col-12 col-md-6 col-lg-4">
                            <label class="form-label fw-semibold">
                                Coluna:
                                <?= htmlspecialchars($header) ?>
                            </label>

                            <select class="form-select" name="mapa[<?= $index ?>]">
                                <option value="">Ignorar</option>
                                <option value="codigo">Código</option>
                                <option value="codigo_barras">Código de Barras</option>
                                <option value="descricao">Descrição</option>
                                <option value="grupo">Grupo</option>
                                <option value="preco">Preço</option>
                                <option value="custo_compra">Custo de Compra</option>
                                <option value="ultima_compra">Última Compra</option>
                                <option value="quantidade">Quantidade</option>
                                <option value="codigo_fornecedor">Código Fornecedor</option>
                                <option value="fornecedor">Fornecedor</option>
                            </select>
                        </div>
                    <?php endforeach; ?>

                    <div class="col-12">
                        <button type="submit" class="btn btn-success">
                            <i class="fa fa-check me-2"></i>Confirmar mapeamento
                        </button>

                        <a href="includes/admin/actions/produtos_limpar_preview.php" class="btn btn-outline-secondary">
                            Cancelar
                        </a>
                    </div>

                </div>
            </form>

        </div>
    </div>

<?php endif; ?>

<?php if (!empty($importPreview)): ?>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-1">Pré-visualização</h4>
            <small class="text-muted">
                <?= count($importPreview) ?> produto(s) pronto(s) para importar
            </small>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-3 p-md-4">

            <div class="d-flex flex-column gap-3">
                <?php foreach ($importPreview as $produto): ?>
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="row g-3 align-items-center">

                                <div class="col-12 col-md-3">
                                    <div class="fw-bold">
                                        <?= htmlspecialchars($produto['descricao'] ?? '') ?>
                                    </div>
                                    <div class="small text-muted">
                                        Código:
                                        <?= htmlspecialchars($produto['codigo'] ?? '') ?>
                                    </div>
                                    <div class="small text-muted">
                                        Barras:
                                        <?= htmlspecialchars($produto['codigo_barras'] ?? '') ?>
                                    </div>
                                </div>

                                <div class="col-6 col-md-2">
                                    <div class="small text-muted">Grupo</div>
                                    <div>
                                        <?= htmlspecialchars($produto['grupo'] ?? '') ?>
                                    </div>
                                </div>

                                <div class="col-6 col-md-2">
                                    <div class="small text-muted">Preço</div>
                                    <div class="fw-semibold text-primary">
                                        R$
                                        <?= number_format((float) ($produto['preco'] ?? 0), 2, ',', '.') ?>
                                    </div>
                                </div>

                                <div class="col-6 col-md-2">
                                    <div class="small text-muted">Custo</div>
                                    <div>
                                        R$
                                        <?= number_format((float) ($produto['custo_compra'] ?? 0), 2, ',', '.') ?>
                                    </div>
                                </div>

                                <div class="col-6 col-md-1">
                                    <div class="small text-muted">Qtd</div>
                                    <div>
                                        <?= number_format((float) ($produto['quantidade'] ?? 0), 4, ',', '.') ?>
                                    </div>
                                </div>

                                <div class="col-12 col-md-2">
                                    <div class="small text-muted">Fornecedor</div>
                                    <div>
                                        <?= htmlspecialchars($produto['fornecedor'] ?? '') ?>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <form action="includes/admin/actions/produtos_importar.php" method="POST" class="mt-4">
                <div class="d-flex flex-column flex-md-row gap-2">
                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-check me-2"></i>Confirmar importação
                    </button>

                    <a href="includes/admin/actions/produtos_limpar_preview.php" class="btn btn-outline-secondary">
                        Cancelar pré-visualização
                    </a>
                </div>
            </form>

        </div>
    </div>

<?php endif; ?>