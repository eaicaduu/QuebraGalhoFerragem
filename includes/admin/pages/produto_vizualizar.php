<?php
$headers = $_SESSION['import_headers'] ?? [];
$rows = $_SESSION['import_rows'] ?? [];
$nomeArquivo = $_SESSION['import_arquivo_nome'] ?? '';

if (empty($headers) || empty($rows)) {
    ?>
    <div class="alert alert-warning">
        Nenhum produto importado foi encontrado para visualização.
    </div>
    <?php
    return;
}

$db = new Database();
$pdo = $db->getConnection();

$stmt = $pdo->query("SELECT codigo FROM produtos WHERE codigo IS NOT NULL");
$codigosExistentes = $stmt->fetchAll(PDO::FETCH_COLUMN);

$codigosExistentes = array_map('strval', $codigosExistentes);

?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="mb-1">Visualizar Importados</h3>
        <small class="text-muted">
            Arquivo:
            <?= htmlspecialchars($nomeArquivo) ?> -
            <?= count($rows) ?> linha(s)
        </small>
    </div>
    <div class="text-end">
        <button id="btnCancelarImportacao" class="btn btn-secondary">
            <i class="fa fa-times me-1"></i>Cancelar
        </button>
    </div>
</div>

<div class="d-flex flex-column gap-3">

    <div class="shadow-sm rounded p-3" style="max-height: 50vh; overflow-y: auto;">
        <?php foreach ($rows as $index => $row): ?>

            <?php
            $codigoRaw = $row[0] ?? '';

            preg_match('/^\d{1,5}/', $codigoRaw, $match);

            $codigo = $match[0] ?? '';

            $jaExiste = in_array((string) $codigo, $codigosExistentes, true);

            $descricaoRaw = $row[0] ?? '';

            preg_match('/%(.+)/', $descricaoRaw, $match);

            $descricao = isset($match[1]) ? trim($match[1]) : $descricaoRaw;

            $grupo = $row[1] ?? '';
            $precoRaw = $row[2] ?? '';

            $precoLimpo = substr($precoRaw, 0, 12);

            $precoLimpo = str_replace(',', '.', $precoLimpo);

            $precoNumero = (float) $precoLimpo;

            $preco = number_format($precoNumero, 2, ',', '.');
            ?>

            <div class="card border-0 bg-body-secondary mb-2 <?= $jaExiste ? 'opacity-50' : '' ?>">
                <div class="card-body p-3">

                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2">

                        <div class="flex-fill">

                            <div class="fw-semibold">
                                <?= htmlspecialchars($descricao ?: '-') ?>
                            </div>

                            <div class="text-muted small">
                                Código: <?= htmlspecialchars($codigo ?: '-') ?>
                                Grupo: <?= htmlspecialchars($grupo ?: '-') ?>
                            </div>

                        </div>

                        <div class="text-md-center">
                            <?php if ($preco !== ''): ?>
                                <span class="fw-semibold">
                                    Preço: <?= htmlspecialchars($preco) ?>
                                </span>
                            <?php endif; ?>
                        </div>

                        <button class="btn btn-sm btnSalvarImportado 
                        <?= $jaExiste ? 'btn-secondary' : 'btn-success' ?>" <?= $jaExiste ? 'disabled' : '' ?>
                            data-codigo="<?= htmlspecialchars($codigo) ?>"
                            data-descricao="<?= htmlspecialchars($descricao) ?>"
                            data-grupo="<?= htmlspecialchars($grupo) ?>" data-preco="<?= htmlspecialchars($precoNumero) ?>">

                            <?php if ($jaExiste): ?>
                                <i class="fa fa-check me-1"></i>Cadastrado
                            <?php else: ?>
                                <i class="fa fa-save me-1"></i>Salvar
                            <?php endif; ?>
                        </button>
                    </div>
                </div>
            </div>

        <?php endforeach; ?>
    </div>

</div>