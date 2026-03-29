<?php
$acao = $_GET['acao'] ?? 'nova categoria';
$id = (int) ($_GET['id'] ?? 0);
$modoEdicao = $acao === 'editar categoria' && $id > 0;

$nome = '';
$ativo = 1;

if ($modoEdicao) {
    require_once __DIR__ . '/../../../app/models/geral/buscar.php';

    $categoria = buscar('categorias', $id);

    $nome = $categoria['nome'] ?? '';
    $ativo = $categoria['ativo'] ?? 1;
}

$formId = $modoEdicao ? 'formEditarCategoria' : 'formNovaCategoria';
$btnId = $modoEdicao ? 'btnEditarCategoria' : 'btnSalvarCategoria';
?>

<div class="card shadow-sm border-0">
    <div class="card-body">

        <form id="<?= $formId ?>" method="POST" enctype="multipart/form-data">

            <input type="hidden" name="id" value="<?= $id ?>">

            <div class="mb-3">
                <label class="form-label">Nome</label>
                <input type="text" name="nome" class="form-control" value="<?= htmlspecialchars($nome) ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="ativo" class="form-select">
                    <option value="1" <?= $ativo ? 'selected' : '' ?>>Ativo</option>
                    <option value="0" <?= !$ativo ? 'selected' : '' ?>>Inativo</option>
                </select>
            </div>

            <div class="col-12 mt-4">
                <div class="d-flex flex-column flex-md-row gap-2">

                    <button type="submit" id="<?= $btnId ?>" class="btn btn-primary">
                        <i class="fa fa-save me-2"></i>
                        <?= $modoEdicao ? 'Atualizar' : 'Salvar' ?>
                    </button>

                    <a href="admin.php?page=categoria&acao=todas categorias" class="btn btn-secondary">
                        <i class="fa fa-times me-2"></i>
                        Cancelar
                    </a>

                </div>
            </div>

        </form>

    </div>
</div>