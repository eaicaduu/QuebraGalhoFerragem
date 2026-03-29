<?php
$arquivoImportado = $_SESSION['import_arquivo_nome'] ?? '';
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1">Importar Produtos</h3>
        <small class="text-muted">Importe os produtos para o sistema</small>
    </div>
</div>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-body p-3">
        <form id="formImportacao" method="POST" enctype="multipart/form-data">
            <div class="row g-3">

                <div class="col-12">
                    <label for="arquivo_importacao" class="form-label fw-semibold">Arquivo</label>
                    <input type="file" class="form-control" id="arquivo_importacao" name="arquivo_importacao"
                        accept=".pdf,.txt">
                    <div class="form-text">Formatos aceitos: PDF e TXT.</div>

                    <?php if (!empty($arquivoImportado)): ?>
                        <div class="mt-2 p-2 border rounded bg-light d-flex justify-content-between align-items-center">

                            <div>
                                <small class="text-muted d-block">Arquivo carregado:</small>
                                <strong>
                                    <?= htmlspecialchars($arquivoImportado) ?>
                                </strong>
                            </div>

                            <a href="admin.php?page=importar&acao=visualizar" class="btn btn-sm btn-dark">
                                <i class="fa fa-eye me-1"></i>Visualizar
                            </a>

                        </div>
                    <?php endif; ?>
                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-primary" id="btnImportar">
                        <i class="fa fa-upload me-2"></i>Enviar arquivo
                    </button>
                </div>

                <div class="col-12" id="mensagemImportacao"></div>

            </div>
        </form>
    </div>
</div>