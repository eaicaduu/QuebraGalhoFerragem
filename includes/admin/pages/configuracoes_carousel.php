<?php
$db = new Database();
$pdo = $db->getConnection();

$stmt = $pdo->prepare("SELECT * FROM carousel ORDER BY id DESC LIMIT 1");
$stmt->execute();
$carousel = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<div class="card shadow-sm text-center p-4 mx-auto" style="max-width: 700px;">
    <div class="card-body">

        <i class="fa-solid fa-image text-warning fs-1 mb-3"></i>

        <h3 class="card-title mb-2">Carousel da Página Inicial</h3>
        <p class="text-muted mb-4">
            Defina a imagem que aparecerá na entrada do site.
        </p>

        <form action="acoes/salvar_carousel.php" method="POST" enctype="multipart/form-data" class="d-flex flex-column gap-3">

            <div class="text-start">
                <label class="form-label"><strong>Imagem atual:</strong></label>

                <div class="border rounded p-3 text-center bg-light">
                    <?php if (!empty($carousel['imagem']) && file_exists(__DIR__ . '/../' . $carousel['imagem'])): ?>
                        <img src="<?= htmlspecialchars($carousel['imagem']) ?>"
                             alt="Imagem do carousel"
                             class="img-fluid rounded"
                             style="max-height: 250px; object-fit: cover;">
                    <?php else: ?>
                        <div class="text-muted">Nenhuma imagem cadastrada</div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="text-start">
                <label for="imagem" class="form-label"><strong>Nova imagem:</strong></label>
                <input type="file" name="imagem" id="imagem" class="form-control" accept="image/*">
            </div>

            <div class="text-start">
                <label for="ativo" class="form-label"><strong>Status:</strong></label>
                <select name="ativo" id="ativo" class="form-select">
                    <option value="1" <?= isset($carousel['ativo']) && $carousel['ativo'] == 1 ? 'selected' : '' ?>>Ativo</option>
                    <option value="0" <?= isset($carousel['ativo']) && $carousel['ativo'] == 0 ? 'selected' : '' ?>>Inativo</option>
                </select>
            </div>

            <div class="text-start">
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-save me-2"></i>Salvar imagem do carousel
                </button>
            </div>

        </form>

    </div>
</div>