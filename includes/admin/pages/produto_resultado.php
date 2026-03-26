<?php if (empty($produtos)): ?>

    <div class="col-12">
        <div class="alert bg-body-secondary text-center">
            Nenhum produto encontrado.
        </div>
    </div>

<?php else: ?>

    <?php foreach ($produtos as $produto): ?>

        <div class="card border-0 bg-body-secondary mb-2">
            <div class="card-body py-2 px-3">

                <div class="d-flex align-items-center justify-content-between gap-2 overflow-hidden"
                    title="<?= htmlspecialchars($produto['nome']) ?>">

                    <div class="d-flex align-items-center gap-3 flex-grow-1 overflow-hidden" style="min-width: 0;">

                        <div style="width:50px; height:50px; flex: 0 0 50px;">
                            <?php if (!empty($produto['imagem'])): ?>
                                <img src="app/<?= htmlspecialchars($produto['imagem']) ?>" class="img-fluid rounded"
                                    style="width:50px; height:50px; object-fit:cover;">
                            <?php else: ?>
                                <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                    style="width:50px; height:50px;">
                                    <i class="fa fa-image text-muted"></i>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="flex-grow-1 overflow-hidden" style="min-width: 0;">

                            <div class="fw-semibold"
                                style="display:block; width:100%; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;"
                                title="<?= htmlspecialchars($produto['nome']) ?>">
                                <?= (int) $produto['id'] ?> -
                                <?= htmlspecialchars($produto['nome']) ?>
                            </div>

                            <div class="d-flex flex-wrap align-items-center gap-2 mt-1">
                                <span class="badge <?= !empty($produto['ativo']) ? 'bg-success' : 'bg-danger' ?>">
                                    <i class="fa fa-circle me-1"></i>
                                    <?= !empty($produto['ativo']) ? 'Ativo' : 'Inativo' ?>
                                </span>

                                <span class="badge <?= (int) ($produto['estoque'] ?? 0) > 0 ? 'bg-primary' : 'bg-secondary' ?>">
                                    <i class="fa fa-box me-1"></i>
                                    <?= (int) ($produto['estoque'] ?? 0) > 0
                                        ? (int) $produto['estoque']
                                        : 'Sem estoque' ?>
                                </span>
                            </div>

                        </div>

                    </div>

                    <div class="d-flex gap-2 flex-shrink-0">
                        <a href="admin.php?page=produtos&acao=editar&id=<?= $produto['id'] ?>" class="btn btn-dark btn-sm">
                            <i class="fa fa-edit"></i>
                        </a>

                        <button type="button" class="btn btn-danger btn-sm btn-excluir-produto" data-id="<?= $produto['id'] ?>">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>

                </div>

            </div>
        </div>

    <?php endforeach; ?>

<?php endif; ?>