document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('formCarousel') || document.getElementById('formEditarCarousel');
    const inputImagens = document.getElementById('imagens');
    const btnSalvar = document.getElementById('btnSalvarCarousel') || document.getElementById('btnEditarCarousel');
    const mensagem = document.getElementById('mensagemCarousel');
    const previewContainer = document.getElementById('previewContainer');
    const previewGrid = document.getElementById('previewGrid');
    const btnCancelar = document.getElementById('btnEditarCancelar');

    if (!form || !inputImagens || !btnSalvar || !mensagem || !previewContainer || !previewGrid) {
        return;
    }

    const modoEdicao = btnSalvar.id === 'btnEditarCarousel';

    function validarArquivo() {
        const temArquivo = inputImagens.files && inputImagens.files.length > 0;

        if (modoEdicao) {
            btnSalvar.disabled = false;
            return;
        }

        btnSalvar.disabled = !temArquivo;
    }

    validarArquivo();
    inputImagens.addEventListener('change', validarArquivo);

    if (btnCancelar) {
        btnCancelar.addEventListener('click', function () {
            window.location.href = 'admin.php?page=configuracao&acao=imagens carousel';
        });
    }

    function mostrarMensagem(texto, tipo = 'success', mostrarReload = false, redirect = 'admin.php?page=configuracao&acao=imagens carousel') {
        mensagem.innerHTML = `
            <div class="alert alert-${tipo} fade show mt-3 d-flex justify-content-between align-items-center" role="alert">
                <div>${texto}</div>
                <div class="d-flex align-items-center gap-2">
                    ${mostrarReload ? `
                        <button type="button" class="btn-close btn-reload" aria-label="Recarregar"></button>
                    ` : ''}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
                </div>
            </div>
        `;

        const btnReload = mensagem.querySelector('.btn-reload');

        if (btnReload) {
            btnReload.addEventListener('click', function () {
                if (redirect) {
                    window.location.href = redirect;
                } else {
                    window.location.reload();
                }
            });
        }
    }

    function limparMensagem() {
        mensagem.innerHTML = '';
    }

    function renderPreview(files) {
        previewGrid.innerHTML = '';

        if (!files || files.length === 0) {
            previewContainer.classList.add('d-none');
            return;
        }

        previewContainer.classList.remove('d-none');

        Array.from(files).forEach(file => {
            if (!file.type.startsWith('image/')) return;

            const reader = new FileReader();

            reader.onload = function (e) {
                const col = document.createElement('div');
                col.className = 'col-12';

                col.innerHTML = `
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-2">
                            <div class="border rounded bg-white p-2 text-center mb-2">
                                <img src="${e.target.result}" alt="${file.name}" class="img-fluid rounded pe-none" style="height:120px; width:100%; object-fit:cover;">
                            </div>
                            <div class="small text-muted text-truncate" title="${file.name}">
                                ${file.name}
                            </div>
                        </div>
                    </div>
                `;

                previewGrid.appendChild(col);
            };

            reader.readAsDataURL(file);
        });
    }

    inputImagens.addEventListener('change', function () {
        renderPreview(this.files);
        validarArquivo();
    });

    btnSalvar.addEventListener('click', function () {
        if (!modoEdicao && (!inputImagens.files || inputImagens.files.length === 0)) {
            mostrarMensagem('Selecione pelo menos uma imagem.', 'danger', false, null);
            return;
        }

        const formData = new FormData(form);

        btnSalvar.disabled = true;
        btnSalvar.innerHTML = modoEdicao
            ? '<i class="fa fa-spinner fa-spin me-2"></i>Atualizando...'
            : '<i class="fa fa-spinner fa-spin me-2"></i>Salvando...';

        fetch(
            modoEdicao
                ? './app/models/carousel/carousel_editar.php'
                : './app/models/carousel/carousel_salvar.php',
            {
                method: 'POST',
                body: formData
            }
        )
            .then(async response => {
                const texto = await response.text();

                let data;
                try {
                    data = JSON.parse(texto);
                } catch (e) {
                    console.error('Resposta recebida:', texto);
                    throw new Error('A resposta do servidor não está em JSON.');
                }

                if (!response.ok || data.status === false) {
                    throw new Error(data.mensagem || 'Erro ao salvar as imagens.');
                }

                return data;
            })
            .then(data => {
                limparMensagem();

                mostrarMensagem(
                    data.mensagem || (modoEdicao ? 'Imagem atualizada com sucesso!' : 'Imagens salvas com sucesso!'),
                    'success',
                    true,
                    modoEdicao ? data.redirect : null
                );

                btnSalvar.innerHTML = modoEdicao
                    ? '<i class="fa fa-check me-1"></i><span>Atualizada</span>'
                    : '<i class="fa fa-check me-1"></i><span>Salvo</span>';

                if (modoEdicao && btnCancelar) {
                    btnCancelar.style.display = 'none';
                }

                form.reset();
                previewGrid.innerHTML = '';
                previewContainer.classList.add('d-none');
                validarArquivo();
            })
            .catch(error => {
                limparMensagem();

                mostrarMensagem(error.message || 'Erro ao salvar as imagens.', 'danger', true, null);

                btnSalvar.disabled = false;
                btnSalvar.innerHTML = modoEdicao
                    ? '<i class="fa fa-save me-1"></i><span>Atualizar</span>'
                    : '<i class="fa fa-save me-1"></i><span>Salvar</span>';

                validarArquivo();
            });
    });
});