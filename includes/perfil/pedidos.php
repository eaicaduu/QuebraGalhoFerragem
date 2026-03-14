<?php

$pedidos = [];

if (!empty($_SESSION['user_id'])) {

    $db = new Database();
    $conn = $db->getConnection();

    if (!$conn) {
        echo json_encode(['success' => false, 'error' => 'erro_conexao']);
        exit;
    }

    runMigrations($conn);

    $stmt = $conn->prepare("
        SELECT id, total, status, criado_em
        FROM pedidos
        WHERE usuario_id = :user
        ORDER BY criado_em DESC
    ");

    $stmt->bindParam(':user', $_SESSION['user_id']);
    $stmt->execute();

    $pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<div class="card shadow-sm text-center p-4 mx-auto" style="max-width: 600px;">
    <div class="card-body">

        <h5 class="mb-3">
            <i class="fa fa-box me-2"></i>
            Meus Pedidos
        </h5>

        <?php if (empty($pedidos)) { ?>

            <div class="text-center py-4 text-muted">
                <i class="fa fa-box-open fa-2x mb-2"></i>
                <p class="mb-0">Você ainda não fez nenhum pedido.</p>
            </div>

        <?php } else { ?>

            <div class="table-responsive">

                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Data</th>
                            <th>Status</th>
                            <th>Total</th>
                            <th></th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php foreach ($pedidos as $pedido) { ?>

                            <tr>

                                <td>
                                    #
                                    <?= $pedido['id'] ?>
                                </td>

                                <td>
                                    <?= date('d/m/Y', strtotime($pedido['criado_em'])) ?>
                                </td>

                                <td>
                                    <span class="badge bg-secondary">
                                        <?= htmlspecialchars($pedido['status']) ?>
                                    </span>
                                </td>

                                <td>
                                    R$
                                    <?= number_format($pedido['total'], 2, ',', '.') ?>
                                </td>

                                <td class="text-end">
                                    <a href="pedido.php?id=<?= $pedido['id'] ?>" class="btn btn-sm btn-outline-primary">
                                        Ver
                                    </a>
                                </td>

                            </tr>

                        <?php } ?>
                    </tbody>

                </table>

            </div>

        <?php } ?>

    </div>
</div>