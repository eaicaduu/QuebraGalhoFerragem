<?php

$db = new Database();
$pdo = $db->getConnection();

$stmt = $pdo->prepare("
    SELECT id, nome, preco, estoque, criado_em
    FROM produtos
    ORDER BY id DESC
");

$stmt->execute();
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h3 class="mb-1">Produtos</h3>
        <small class="text-muted">Gerencie os produtos da loja</small>
    </div>

</div>

<div class="card shadow-sm border-0">

    <div class="card-body">

        <input type="text" class="form-control mb-3" placeholder="Pesquisar produto...">

        <?php if (empty($produtos)): ?>

            <div class="text-center py-5 text-muted">
                Nenhum produto encontrado
            </div>

        <?php else: ?>

            <?php foreach ($produtos as $produto): ?>

                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body">

                        <div class="row align-items-center">

                            <div class="col-md-3 mb-2 mb-md-0">

                                <strong><?= htmlspecialchars($produto['nome']) ?></strong>

                                <div class="text-muted small">
                                    ID #<?= $produto['id'] ?>
                                </div>

                            </div>

                            <div class="col-md-2 mb-2 mb-md-0">

                                <span class="fw-bold text-primary">
                                    R$ <?= number_format($produto['preco'], 2, ',', '.') ?>
                                </span>

                            </div>

                            <div class="col-md-2 mb-2 mb-md-0">

                                <?php if ($produto['estoque'] > 10): ?>

                                    <span class="badge bg-success">
                                        <?= $produto['estoque'] ?> unidades
                                    </span>

                                <?php elseif ($produto['estoque'] > 0): ?>

                                    <span class="badge bg-warning text-dark">
                                        <?= $produto['estoque'] ?> baixo
                                    </span>

                                <?php else: ?>

                                    <span class="badge bg-danger">
                                        Sem estoque
                                    </span>

                                <?php endif; ?>

                            </div>

                            <div class="col-md-3 mb-2 mb-md-0">

                                <small class="text-muted">
                                    Criado em
                                </small>

                                <div>
                                    <?= date('d/m/Y', strtotime($produto['criado_em'])) ?>
                                </div>

                            </div>

                            <div class="col-md-2 text-md-end">

                                <a href="admin.php?page=produtos&acao=editar&id=<?= $produto['id'] ?>"
                                    class="btn btn-dark btn-sm">
                                    <i class="fa fa-edit"></i>
                                </a>

                                <a href="admin.php?page=produtos&acao=excluir&id=<?= $produto['id'] ?>"
                                    class="btn btn-dark btn-sm">
                                    <i class="fa fa-trash"></i>
                                </a>

                            </div>

                        </div>

                    </div>
                </div>

            <?php endforeach; ?>

        <?php endif; ?>

    </div>

</div>