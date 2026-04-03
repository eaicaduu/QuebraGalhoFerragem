<?php
require_once __DIR__ . '/../../app/models/geral/listar.php';
require_once __DIR__ . '/../../app/models/geral/imagem.php';

$pesquisa = $pesquisa ?? ($_GET['pesquisa'] ?? null);
$produtos = listar('produtos', $pesquisa, true, 'id DESC', ['nome']);
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

        <?php $imagemProduto = obterImagem($produto['imagem'] ?? null, 'images/default.png', 'app/'); ?>

        <div class="col-6 col-md-4 col-lg-3">
            <div class="card border-0 shadow-sm h-100">

                <div class="d-flex align-items-center justify-content-center" style="height:180px;">

                    <img src="<?= htmlspecialchars($imagemProduto, ENT_QUOTES, 'UTF-8') ?>" class="w-100 h-100 pe-none"
                        style="object-fit:contain;" alt="<?= htmlspecialchars($produto['nome']) ?>"
                        onerror="this.onerror=null;this.src='images/default.png';">

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