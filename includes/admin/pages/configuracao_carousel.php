<?php
require_once __DIR__ . '/../../../app/models/geral/listar.php';

$carousels = listar('carousel', null, false, 'id DESC', ['nome']);

$acao = $_GET['acao'] ?? 'carousel';
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$modoEdicao = $acao === 'editar' && $id > 0;

$carouselAtual = null;
$imagemAtual = '';
$ativoPadrao = 1;
$contador = 1;

if ($modoEdicao) {
    require_once __DIR__ . '/../../../app/models/carousel/carousel_buscar.php';

    $carouselAtual = buscarCarousel($id);

    if ($carouselAtual) {
        $imagemAtual = $carouselAtual['imagem'] ?? '';
        $ativoPadrao = isset($carouselAtual['ativo']) ? (int) $carouselAtual['ativo'] : 1;
    }
}
?>

<div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-2 mb-2">

    <div>
        <h3 class="mb-1">Carousel</h3>
        <small class="text-muted text-nowrap">Gerencie as imagens exibidas na página inicial</small>
    </div>

    <div class="w-100 w-lg-auto text-end d-flex justify-content-end gap-2">
        <button type="button" id="<?= $modoEdicao ? 'btnEditarCarousel' : 'btnSalvarCarousel' ?>"
            class="btn btn-sm <?= $modoEdicao ? 'btn-primary' : 'btn-success' ?>"
            <?= !$modoEdicao ? 'disabled' : '' ?>>
            <i class="fa fa-save me-1"></i>
            <span>
                <?= $modoEdicao ? 'Atualizar' : 'Salvar' ?>
            </span>
        </button>

        <?php if ($modoEdicao): ?>
            <button type="button" id="btnEditarCancelar" class="btn btn-secondary btn-sm">
                <i class="fa fa-times me-1"></i>
                <span>Cancelar</span>
            </button>
        <?php endif; ?>

        <button type="button" id="btnSelecionarEditarCarousel"
            class="btn btn-dark btn-sm <?= empty($carousels) ? 'disabled opacity-50' : '' ?>" <?= empty($carousels) ? 'disabled' : '' ?>>
            <i class="fa fa-edit me-1"></i>Editar
        </button>

        <button type="button" id="btnSelecionarExcluirCarousel"
            class="btn btn-danger btn-sm <?= empty($carousels) ? 'disabled opacity-50' : '' ?>" <?= empty($carousels) ? 'disabled' : '' ?>>
            <i class="fa fa-trash me-1"></i>Excluir
        </button>
    </div>
</div>

<div class="card shadow-sm border-0 mb-4">
    <div class="card-body p-4">

        <form id="<?= $modoEdicao ? 'formEditarCarousel' : 'formCarousel' ?>" method="POST"
            enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $modoEdicao ? $id : '' ?>">
            <input type="hidden" name="modo" value="<?= $modoEdicao ? 'editar' : 'novo' ?>">
            <input type="hidden" name="imagem_atual" value="<?= htmlspecialchars($imagemAtual) ?>">

            <div class="row">

                <div class="col-12 mb-2">
                    <label for="imagens" class="form-label">
                        <?= $modoEdicao ? 'Alterar imagem' : 'Adicionar imagens' ?>
                    </label>
                    <input type="file" name="imagens[]" id="imagens" class="form-control form-control-sm m-0" accept="image/*"
                        <?= $modoEdicao ? '' : 'multiple' ?>>
                    <p class="text-muted mt-1">Tamanho recomendado 1462 x 731</p>
                </div>

                <div class="row">

                    <!-- IMAGEM ATUAL -->
                    <?php if ($modoEdicao && !empty($imagemAtual)): ?>
                        <div class="col-12 col-md-4 mb-0 mb-2">
                            <div class="border rounded p-3 bg-light h-100">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-body p-2">
                                        <div class="border rounded bg-white p-2 text-center mb-2">
                                            <img src="app/<?= htmlspecialchars($imagemAtual) ?>"
                                                class="img-fluid rounded pe-none"
                                                style="height:120px; width:100%; object-fit:cover;">
                                        </div>
                                        <div class="small text-muted text-truncate">
                                            Imagem atual
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- PREVIEW -->
                    <div class="col-12 col-md-4 mb-2">
                        <div class="border rounded p-3 bg-light text-center d-none h-100" id="previewContainer">
                            <div class="row" id="previewGrid"></div>
                        </div>
                    </div>

                </div>

                <div class="col-12">
                    <div id="mensagemCarousel"></div>
                </div>

                <!-- STATUS -->
                <div class="col-12 col-lg-4">
                    <label for="ativo_padrao" class="form-label fw-semibold">Status da imagem</label>
                    <select name="ativo_padrao" id="ativo_padrao" class="form-select form-select-sm">
                        <option value="1" <?= $ativoPadrao === 1 ? 'selected' : '' ?>>Ativo</option>
                        <option value="0" <?= $ativoPadrao === 0 ? 'selected' : '' ?>>Inativo</option>
                    </select>

                </div>
            </div>
    </div>
    </form>
</div>

<div class="d-flex justify-content-between align-items-center mb-3">

    <div>
        <h4 class="mb-1">Imagens</h4>
        <small class="text-muted" id="contadorCarousel">
            <?php
            $total = count($carousels);

            if ($total === 0) {
                echo 'Nenhuma imagem no carousel';
            } elseif ($total === 1) {
                echo '1 imagem no carousel';
            } else {
                echo $total . ' imagens no carousel';
            }
            ?>
        </small>
    </div>

    <div class="d-flex gap-3">

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

        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">

                <div class="row g-3">
                    <?php foreach ($carousels as $item): ?>
                        <?php
                        $imagemRelativa = $item['imagem'] ?? '';
                        $ativo = (int) ($item['ativo'] ?? 1);
                        $criadoEm = $item['criado_em'] ?? null;

                        $dataFormatada = $criadoEm
                            ? date('d/m/Y', strtotime($criadoEm))
                            : '';
                        ?>

                        <div class="col-12 col-md-4" data-carousel-id="<?= (int) $item['id'] ?>">
                            <div class="border rounded p-3 bg-light h-100">
                                <div class="carousel-item-select card border-0 shadow-sm h-100 position-relative text-center"
                                    data-id="<?= (int) $item['id'] ?>" style="cursor:pointer;">

                                    <span class="position-absolute top-0 end-0 m-1 badge d-flex align-items-center gap-1 
                                        <?= $ativo ? 'bg-success' : 'bg-secondary' ?>" style="font-size:10px; z-index:2;">
                                        <?php if ($dataFormatada): ?>
                                            <i class="fa fa-calendar"></i>
                                            <?= $dataFormatada ?>
                                        <?php endif; ?>
                                    </span>

                                    <div class="carousel-select-overlay d-none">
                                        Selecionar
                                    </div>

                                    <div class="card-body p-2">
                                        <div class="border rounded bg-white p-2 text-center mb-2">
                                            <img src="app/<?= htmlspecialchars($imagemRelativa) ?>"
                                                class="img-fluid rounded pe-none"
                                                style="height:120px; width:100%; object-fit:cover;">
                                        </div>

                                        <div class="small text-muted text-truncate">
                                            Imagem <?= $contador ?>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <?php $contador++; ?>
                        
                    <?php endforeach; ?>
                </div>
            </div>

        </div>
    </div>

    </div>

<?php endif; ?>