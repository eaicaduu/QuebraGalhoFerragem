<div class="card shadow-sm text-center p-4 mx-auto" style="max-width: 600px;">
    <div class="card-body">

        <i class="fa-solid fa-user-circle text-warning fs-1 mb-3"></i>
        <h3 class="card-title mb-3">
            Bem-vindo(a), <?= htmlspecialchars(explode(' ', trim($user['nome']))[0]) ?>!
        </h3>

        <form id="perfilForm" class="d-flex flex-column gap-3">

            <div class="text-start position-relative mb-3">
                <label for="nome" class="form-label"><strong>Nome:</strong></label>
                <input type="text" placeholder="Informe o seu nome" id="nome" name="nome" class="form-control"
                    value="<?= htmlspecialchars($user['nome']) ?>">
                <i class="fa fa-check text-success position-absolute check-icon"
                    style="top: 50%; right: 12px; transform: translateY(50%); cursor:pointer;" onclick="salvarCampo('nome')"></i>
            </div>

            <div class="text-start position-relative mb-3">
                <label for="email" class="form-label"><strong>Email:</strong></label>
                <input type="email" placeholder="Informe o seu email" id="email" name="email" class="form-control"
                    value="<?= htmlspecialchars($user['email']) ?>">
                <i class="fa fa-check text-success position-absolute check-icon"
                     style="top: 50%; right: 12px; transform: translateY(50%); cursor:pointer;" onclick="salvarCampo('email')"></i>
            </div>

            <div class="text-start position-relative mb-3">
                <label for="telefone" class="form-label"><strong>Telefone:</strong></label>
                <input type="text" placeholder="Informe o seu telefone" id="telefone" name="telefone"
                    class="form-control" value="<?= htmlspecialchars($user['telefone'] ?? '') ?>">
                <i class="fa fa-check text-success position-absolute check-icon"
                     style="top: 50%; right: 12px; transform: translateY(50%); cursor:pointer;" onclick="salvarCampo('telefone')"></i>
            </div>

            <div id="statusUpdate" class="mt-2 small"></div>
        </form>

    </div>
</div>