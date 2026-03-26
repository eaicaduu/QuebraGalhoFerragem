<?php
$db = new Database();
$pdo = $db->getConnection();

$stmt = $pdo->prepare("
    SELECT 
        p.id,
        p.usuario_id,
        p.total,
        p.status,
        p.criado_em,
        p.atualizado_em,
        u.nome AS cliente
    FROM pedidos p
    LEFT JOIN usuarios u ON u.id = p.usuario_id
    ORDER BY p.criado_em DESC
");

$stmt->execute();
$pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Pedidos</h3>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-dark">
                    <tr>
                        <th>#</th>
                        <th>Cliente</th>
                        <th>Data</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th class="text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>

                    <?php if (empty($pedidos)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-4">
                                Nenhum pedido encontrado.
                            </td>
                        </tr>
                    <?php else: ?>

                        <?php foreach ($pedidos as $pedido): ?>
                            <tr>
                                <td>
                                    <?= (int) $pedido['id'] ?>
                                </td>

                                <td>
                                    <?= htmlspecialchars($pedido['cliente'] ?? 'Usuário removido') ?>
                                </td>

                                <td>
                                    <?= !empty($pedido['criado_em']) ? date('d/m/Y H:i', strtotime($pedido['criado_em'])) : '-' ?>
                                </td>

                                <td>
                                    R$
                                    <?= number_format((float) $pedido['total'], 2, ',', '.') ?>
                                </td>

                                <td>
                                    <?php
                                    switch ($pedido['status']) {
                                        case 'pendente':
                                            echo '<span class="badge bg-warning text-dark">Pendente</span>';
                                            break;

                                        case 'pago':
                                            echo '<span class="badge bg-success">Pago</span>';
                                            break;

                                        case 'cancelado':
                                            echo '<span class="badge bg-danger">Cancelado</span>';
                                            break;

                                        case 'enviado':
                                            echo '<span class="badge bg-primary">Enviado</span>';
                                            break;

                                        case 'entregue':
                                            echo '<span class="badge bg-info text-dark">Entregue</span>';
                                            break;

                                        default:
                                            echo '<span class="badge bg-secondary">' . htmlspecialchars($pedido['status']) . '</span>';
                                            break;
                                    }
                                    ?>
                                </td>

                                <td class="text-center">
                                    <a href="admin.php?page=pedido_detalhe&id=<?= (int) $pedido['id'] ?>"
                                        class="btn btn-sm btn-dark">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                    <?php endif; ?>

                </tbody>
            </table>
        </div>

    </div>
</div>