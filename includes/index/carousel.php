<?php
require_once __DIR__ . '/../../app/models/geral/carousel_listar.php';

try {
    $imagens = listarCarousel(true);

    if (!is_array($imagens) || empty($imagens)) {
        throw new Exception("Sem imagens");
    }

} catch (Throwable $e) {
    $imagens = [
        ['imagem' => 'images/carousel.png']
    ];
}
?>
<div id="carouselCards" class="carousel slide" data-bs-ride="carousel">

    <div class="carousel-indicators">
        <?php foreach ($imagens as $index => $banner): ?>
            <button type="button" 
                    data-bs-target="#carouselCards" 
                    data-bs-slide-to="<?= $index ?>" 
                    class="<?= $index === 0 ? 'active' : '' ?>" 
                    aria-current="<?= $index === 0 ? 'true' : 'false' ?>" 
                    aria-label="Slide <?= $index + 1 ?>">
            </button>
        <?php endforeach; ?>
    </div>

    <div class="carousel-inner">
        <?php foreach ($imagens as $index => $banner): ?>
            <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                <div class="banner-wrapper">
                    <img src="app/<?= htmlspecialchars($banner['imagem']) ?>" 
                         class="banner-img d-block w-100 pe-none"
                         onerror="this.onerror=null;this.src='images/carousel.png';">
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <button class="carousel-control-prev" type="button" data-bs-target="#carouselCards" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
    </button>

    <button class="carousel-control-next" type="button" data-bs-target="#carouselCards" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
    </button>
</div>