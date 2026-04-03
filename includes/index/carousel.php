<?php
require_once __DIR__ . '/../../app/models/geral/listar.php';
require_once __DIR__ . '/../../app/models/geral/imagem.php';

$imagemPadrao = 'images/default.png';
$imagens = listar('carousel', null, true, 'id DESC', ['nome']);

if (!is_array($imagens) || empty($imagens)) {
    $imagens = [
        ['imagem' => $imagemPadrao]
    ];
}

?>

<section class="carousel-section py-2">
    <div class="container">
        <div id="carouselCards" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <?php foreach ($imagens as $index => $banner): ?>
                    <button type="button" data-bs-target="#carouselCards" data-bs-slide-to="<?= (int) $index ?>"
                        class="<?= $index === 0 ? 'active' : '' ?>" aria-current="<?= $index === 0 ? 'true' : 'false' ?>"
                        aria-label="Slide <?= (int) ($index + 1) ?>">
                    </button>
                <?php endforeach; ?>
            </div>

            <div class="carousel-inner rounded-4 overflow-hidden">
                <?php foreach ($imagens as $index => $banner): ?>
                    <?php $srcImagem = obterImagem($banner['imagem'] ?? null, 'images/carousel.png', 'app/'); ?>
                    <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                        <div class="banner-wrapper">
                            <img src="<?= htmlspecialchars($srcImagem, ENT_QUOTES, 'UTF-8') ?>"
                                alt="Banner <?= (int) ($index + 1) ?>" class="banner-img d-block w-100 pe-none"
                                loading="<?= $index === 0 ? 'eager' : 'lazy' ?>"
                                onerror="this.onerror=null;this.src='<?= htmlspecialchars($imagemPadrao, ENT_QUOTES, 'UTF-8') ?>';">
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <button class="carousel-control-prev" type="button" data-bs-target="#carouselCards" data-bs-slide="prev"
                aria-label="Banner anterior">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            </button>

            <button class="carousel-control-next" type="button" data-bs-target="#carouselCards" data-bs-slide="next"
                aria-label="Próximo banner">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
            </button>
        </div>
    </div>
</section>