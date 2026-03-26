<?php
$stmt = $pdo->prepare("SELECT * FROM usuarios ORDER BY id DESC");
$stmt->execute();

$usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>
        <h3 class="mb-1">Usuários</h3>
        <small class="text-muted">Gerencie os usuários da loja</small>
    </div>

</div>

<div class="d-flex flex-column gap-3">
    <div class="card border-0">

        <div class="shadow-sm rounded p-3" style="max-height: 50vh; overflow-y: auto;">

            <div class="card-body p-0">

                <?php if (empty($usuarios)): ?>

                    <div class="text-center py-5 text-muted">
                        Nenhum usuário encontrado
                    </div>

                <?php else: ?>

                    <?php foreach ($usuarios as $usuario): ?>

                        <div class="card border-0 bg-body-secondary mb-2">
                            <div class="card-body py-2 px-3">

                                <div class="d-flex align-items-center justify-content-between gap-2 overflow-hidden">

                                    <div class="d-flex align-items-center gap-3 flex-grow-1 overflow-hidden"
                                        style="min-width: 0;">

                                        <div class="flex-grow-1 overflow-hidden" style="min-width: 0;">
                                            <div class="fw-semibold"
                                                style="display:block; width:100%; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                                <?= (int) ($usuario['id'] ?? 0) ?> -
                                                <?= htmlspecialchars($usuario['nome'] ?? 'Sem nome') ?>
                                            </div>

                                            <div class="d-flex flex-wrap align-items-center gap-2 mt-1">

                                                <span
                                                    class="badge <?= ($usuario['tipo_usuario'] ?? 1) >= 2 ? 'bg-dark' : 'bg-secondary' ?>">
                                                    <i class="fa fa-user me-1"></i>
                                                    <?= ($usuario['tipo_usuario'] ?? 1) >= 2 ? 'Admin' : 'Cliente' ?>
                                                </span>

                                                <span class="badge bg-light text-dark">
                                                    <i class="fa fa-phone me-1"></i>
                                                    <?= htmlspecialchars($usuario['telefone'] ?? 'Sem telefone') ?>
                                                </span>

                                            </div>
                                        </div>

                                    </div>

                                    <?php
                                    $telefone = preg_replace('/\D/', '', $usuario['telefone'] ?? '');
                                    $temTelefone = !empty($telefone);
                                    ?>

                                    <div class="d-flex gap-2 flex-shrink-0">

                                        <a href="admin.php?page=usuarios&acao=editar&id=<?= (int) $usuario['id'] ?>"
                                            class="btn btn-dark btn-sm">
                                            <i class="fa fa-edit"></i>
                                        </a>

                                        <button type="button" class="btn btn-danger btn-sm btn-excluir-usuario"
                                            data-id="<?= (int) $usuario['id'] ?>">
                                            <i class="fa fa-trash"></i>
                                        </button>

                                        <a href="<?= $temTelefone ? 'https://wa.me/55' . $telefone : '#' ?>" target="_blank"
                                            class="btn <?= $temTelefone ? 'btn-success' : 'btn-secondary disabled' ?> btn-sm px-3"
                                            <?= $temTelefone ? '' : 'tabindex="-1" aria-disabled="true"' ?>
                                            title="
                                            <?= $temTelefone ? 'Chamar no WhatsApp' : 'Sem telefone cadastrado' ?>">
                                                    <i class="fa-brands fa-whatsapp"></i>
                                        </a>


                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>

                <?php endforeach; ?>

            <?php endif; ?>

        </div>

    </div>

</div>