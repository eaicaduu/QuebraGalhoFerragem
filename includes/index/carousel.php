<div id="carouselCards" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">

        <?php for ($i = 1; $i <= 6; $i++): ?>
            <div class="carousel-item <?= $i == 1 ? 'active' : '' ?>">
                <img src="images/banner<?= $i ?>.png"
                     class="d-block w-100 banner-carousel"
                     alt="Banner <?= $i ?>">
            </div>
        <?php endfor; ?>

    </div>

    <button class="carousel-control-prev" type="button" data-bs-target="#carouselCards" data-bs-slide="prev">
        <span class="carousel-control-prev-icon"></span>
    </button>

    <button class="carousel-control-next" type="button" data-bs-target="#carouselCards" data-bs-slide="next">
        <span class="carousel-control-next-icon"></span>
    </button>
</div>