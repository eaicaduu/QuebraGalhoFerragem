<?php
require_once __DIR__ . '/../../../app/models/carousel/carousel_listar.php';

$carousels = listarCarousel();
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1">Carousel</h3>
        <small class="text-muted">Gerencie as imagens exibidas na página inicial</small>
    </div>
</div>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-body p-4">

        <form id="formCarousel" method="POST" enctype="multipart/form-data">
            <div class="row">

                <div class="col-12 mb-4">
                    <label for="imagens" class="form-label fw-semibold">Adicionar imagens</label>
                    <input type="file" name="imagens[]" id="imagens" class="form-control" accept="image/*" multiple>
                    <div class="form-text">
                        Você pode selecionar várias imagens de uma vez.
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <label for="ativo_padrao" class="form-label fw-semibold">Status das novas imagens</label>
                    <select name="ativo_padrao" id="ativo_padrao" class="form-select">
                        <option value="1" selected>Ativo</option>
                        <option value="0">Inativo</option>
                    </select>
                </div>

                <div class="col-12">
                    <div id="mensagemCarousel"></div>
                </div>

                <div class="col-12">
                    <div class="border rounded p-3 bg-light text-center d-none" id="previewContainer">
                        <div class="row g-3" id="previewGrid"></div>
                    </div>
                </div>

                <div class="col-12 pt-2">
                    <div class="d-flex flex-column flex-md-row gap-2">
                        <button type="button" id="btnSalvarCarousel" class="btn btn-success">
                            <i class="fa fa-save me-2"></i>Salvar imagens
                        </button>

                        <a href="admin.php?page=configuracoes&acao=carousel" class="btn btn-secondary">
                            Cancelar
                        </a>
                    </div>
                </div>

            </div>
        </form>

    </div>
</div>

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h4 class="mb-1">Imagens cadastradas</h4>
        <small class="text-muted"><?= count($carousels) ?> imagem(ns) no carousel</small>
    </div>
</div>

<?php if (empty($carousels)): ?>

    <div class="card shadow-sm border-0">
        <div class="card-body text-center py-5 text-muted">
            Nenhuma imagem cadastrada no carousel.
        </div>
    </div>

<?php else: ?>

    <div class="d-flex flex-column gap-3">

        <?php foreach ($carousels as $item): ?>
            <?php
            $imagemRelativa = $item['imagem'] ?? '';

            $baseProjeto = dirname(__DIR__, 3);
            $caminhoImagem = $baseProjeto . '/' . 'app' . '/' . ltrim($imagemRelativa, '/');
            $caminhoImagem = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $caminhoImagem);

            $imagemExiste = !empty($imagemRelativa) && file_exists($caminhoImagem);
            ?>

            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="row align-items-center">

                        <div class="col-12 col-md-2">
                            <div class="border rounded bg-light p-2 text-center">
                                <?php if ($imagemExiste): ?>
                                    <img src="<?= htmlspecialchars($imagemRelativa) ?>" alt="Imagem do carousel"
                                        class="img-fluid rounded" style="max-height: 90px; object-fit: cover;">
                                <?php else: ?>
                                    <div class="text-muted small py-4">Sem imagem</div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="col-12 col-md-3">
                            <div class="text-muted small">
                                <?= htmlspecialchars(basename($imagemRelativa)) ?>
                            </div>

                            <div class="text-muted small mt-1">
                                Caminho: <?= htmlspecialchars($imagemRelativa) ?>
                            </div>

                            <div class="text-muted small">
                                Físico: <?= htmlspecialchars($caminhoImagem) ?>
                            </div>
                        </div>

                        <div class="col-6 col-md-2">
                            <small class="text-muted d-block">Status</small>
                            <?php if ((int) $item['ativo'] === 1): ?>
                                <span class="badge bg-success">Ativo</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Inativo</span>
                            <?php endif; ?>
                        </div>

                        <div class="col-6 col-md-3">
                            <small class="text-muted d-block">Criado em</small>
                            <div>
                                <?= !empty($item['criado_em']) ? date('d/m/Y H:i', strtotime($item['criado_em'])) : '-' ?>
                            </div>
                        </div>

                        <div class="col-12 col-md-2 text-md-end">
                            <div class="d-flex flex-wrap justify-content-md-end gap-2">
                                <a href="admin.php?page=configuracoes&acao=carousel_editar&id=<?= (int) $item['id'] ?>"
                                    class="btn btn-dark btn-sm">
                                    <i class="fa fa-edit"></i>
                                </a>

                                <a href="admin.php?page=configuracoes&acao=carousel_excluir&id=<?= (int) $item['id'] ?>"
                                    class="btn btn-danger btn-sm">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        <?php endforeach; ?>

    </div>

<?php endif; ?>