<?php
require_once __DIR__ . '/../../app/models/geral/produto_listar.php';

$pesquisa = $pesquisa ?? ($_GET['pesquisa'] ?? null);
$produtos = listarProdutos($pesquisa);
?>
<div class="row g-3">

    <?php if (empty($produtos)): ?>
        <div class="col-12">
            <div class="alert bg-body-secondary text-center">
                Nenhum produto encontrado.
            </div>
        </div>
    <?php endif; ?>

    <?php foreach ($produtos as $produto): ?>

        <div class="col-6 col-md-4 col-lg-3">
            <div class="card border-0 shadow-sm h-100">

                <div class="bg-light d-flex align-items-center justify-content-center" style="height:180px;">

                    <?php if (!empty($produto['imagem'])): ?>
                        <img src="app/<?= htmlspecialchars($produto['imagem']) ?>" class="img-fluid"
                            style="max-height:160px; object-fit:contain;">
                    <?php else: ?>
                        <i class="fa fa-image fa-2x text-muted"></i>
                    <?php endif; ?>

                </div>

                <div class="card-body d-flex flex-column">

                    <div class="fw-semibold mb-1 text-truncate">
                        <?= htmlspecialchars($produto['nome']) ?>
                    </div>

                    <div class="text-muted small mb-2" style="min-height:40px;">
                        <?= htmlspecialchars(mb_strimwidth($produto['descricao'] ?? '', 0, 60, '...')) ?>
                    </div>

                    <div class="fw-bold mb-2">
                        R$ <?= number_format($produto['preco'], 2, ',', '.') ?>
                    </div>

                    <div class="mb-2">
                        <?php if ($produto['estoque'] > 10): ?>
                            <span class="badge bg-success">Disponível</span>
                        <?php elseif ($produto['estoque'] > 0): ?>
                            <span class="badge bg-warning text-dark">Últimas unidades</span>
                        <?php else: ?>
                            <span class="badge bg-danger">Sem estoque</span>
                        <?php endif; ?>
                    </div>

                    <div class="mt-auto">
                        <button class="btn btn-dark w-100 btn-sm">
                            <i class="fa fa-cart-plus me-2"></i>Adicionar
                        </button>
                    </div>

                </div>

            </div>
        </div>

    <?php endforeach; ?>

</div>