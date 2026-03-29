<?php
require_once __DIR__ . '/../../../app/models/geral/listar.php';

$pesquisa = $pesquisa ?? ($_GET['pesquisa'] ?? null);
$usuarios = listar('usuarios', $pesquisa, false, 'id DESC', ['nome']);
?>

<div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-2 mb-4">

    <div>
        <h3 class="mb-1">Usuários</h3>
        <small class="text-muted text-nowrap">Gerencie os usuários</small>
    </div>

    <div class="w-100 w-lg-auto text-end d-flex justify-content-end gap-2">

        <button id="btnEditarUsuario" class="btn btn-dark btn-sm" disabled>
            <i class="fa fa-edit"></i> Alterar
        </button>

        <button id="btnWhatsappUsuario" class="btn btn-success btn-sm" disabled>
            <i class="fa-brands fa-whatsapp"></i> WhatsApp
        </button>

    </div>
</div>

<div class="d-flex flex-column gap-3">

    <div class="shadow-sm rounded overflow-auto" style="max-height: 50vh;">

        <div class="card-body p-3">

            <div class="position-sticky top-0 z-3 bg-white pt-2 pb-2">
                <div class="position-relative">
                    <i class="fa fa-search position-absolute top-50 translate-middle-y text-muted"
                        style="left: 14px;"></i>

                    <input type="text" id="inputPesquisarUsuario" data-contexto="admin" class="form-control ps-5"
                        placeholder="Digite para pesquisar os usuários.">
                </div>
            </div>

            <?php if (empty($usuarios)): ?>

                <div class="col-12">
                    <div class="alert bg-body-secondary text-center">
                        Nenhum usuário encontrado.
                    </div>
                </div>

            <?php else: ?>

                <?php foreach ($usuarios as $usuario): ?>

                    <?php
                    $telefone = preg_replace('/\D/', '', $usuario['telefone'] ?? '');
                    $temTelefone = !empty($telefone);
                    ?>

                    <div class="card border-0 bg-body-secondary mb-2 item-usuario-selecao" style="cursor: pointer;">
                        <div class="card-body py-2 px-3">

                            <div class="d-flex align-items-center justify-content-between gap-2 overflow-hidden">

                                <div class="d-flex align-items-center gap-3 flex-grow-1 overflow-hidden" style="min-width: 0;">

                                    <input type="radio" name="usuarioSelecionado" class="form-check-input ms-2 radio-usuario"
                                        value="<?= (int) $usuario['id'] ?>" data-id="<?= (int) $usuario['id'] ?>"
                                        data-tipo="<?= (int) ($usuario['tipo_usuario'] ?? 1) ?>"
                                        data-telefone="<?= htmlspecialchars($telefone) ?>">

                                    <div class="flex-grow-1 overflow-hidden" style="min-width: 0;">
                                        <div class="fw-semibold"
                                            style="display:block; width:100%; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;"
                                            title="<?= htmlspecialchars($usuario['nome'] ?? 'Sem nome') ?>">
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

                            </div>

                        </div>
                    </div>

                <?php endforeach; ?>

            <?php endif; ?>

        </div>

    </div>

</div>