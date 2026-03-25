<?php
require_once __DIR__ . '/../../../app/models/geral/produto_listar.php';

$produtos = listarProdutos();
?>

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h3 class="mb-1">Produtos</h3>
        <small class="text-muted">Gerencie os produtos da loja</small>
    </div>

</div>

<div class="d-flex flex-column gap-3">
    <div class="card border-0">

        <div class="border rounded p-3" style="max-height: 40vh; overflow-y: auto;">

            <div class="card-body p-0">

                <?php if (empty($produtos)): ?>

                    <div class="text-center py-5 text-muted">
                        Nenhum produto encontrado
                    </div>

                <?php else: ?>

                    <?php foreach ($produtos as $produto): ?>

                        <div class="card border-0 bg-body-secondary mb-2">
                            <div class="card-body py-2 px-3">

                                <div class="d-flex align-items-center justify-content-between">

                                    <div class="d-flex align-items-center gap-3">

                                        <div style="width:50px; height:50px;">
                                            <?php if (!empty($produto['imagem'])): ?>
                                                <img src="app/<?= htmlspecialchars($produto['imagem']) ?>"
                                                    class="img-fluid rounded"
                                                    style="width:50px; height:50px; object-fit:cover;">
                                            <?php else: ?>
                                                <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                                    style="width:50px; height:50px;">
                                                    <i class="fa fa-image text-muted"></i>
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                        <div>
                                            <div class="fw-semibold">
                                                <?= htmlspecialchars($produto['nome']) ?>
                                            </div>
                                            <small class="text-muted">
                                                Id <?= $produto['id'] ?>
                                            </small>
                                        </div>

                                    </div>

                                    <div class="d-flex gap-2">

                                        <a href="admin.php?page=produtos&acao=editar&id=<?= $produto['id'] ?>"
                                            class="btn btn-dark btn-sm">
                                            <i class="fa fa-edit"></i>
                                        </a>

                                        <button type="button"
                                            class="btn btn-dark btn-sm btn-excluir-produto"
                                            data-id="<?= $produto['id'] ?>">
                                            <i class="fa fa-trash"></i>
                                        </button>

                                    </div>

                                </div>

                            </div>
                        </div>

                    <?php endforeach; ?>

                <?php endif; ?>

            </div>

        </div>

    </div>
</div>