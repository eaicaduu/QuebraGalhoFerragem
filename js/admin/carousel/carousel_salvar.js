document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('formCarousel');
    const inputImagens = document.getElementById('imagens');
    const btnSalvar = document.getElementById('btnSalvarCarousel');
    const mensagem = document.getElementById('mensagemCarousel');
    const previewContainer = document.getElementById('previewContainer');
    const previewGrid = document.getElementById('previewGrid');

    function mostrarMensagem(texto, tipo = 'success') {
        if (!mensagem) return;

        mensagem.innerHTML = `
            <div class="alert alert-${tipo} alert-dismissible fade show mt-3" role="alert">
                ${texto}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
            </div>
        `;
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
                col.className = 'col-6 col-md-3 col-lg-2';

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
    });

    btnSalvar.addEventListener('click', function () {
        if (!inputImagens.files || inputImagens.files.length === 0) {
            mostrarMensagem('Selecione pelo menos uma imagem.', 'danger');
            return;
        }

        const formData = new FormData(form);

        btnSalvar.disabled = true;
        btnSalvar.innerHTML = '<i class="fa fa-spinner fa-spin me-2"></i>Salvando...';

        fetch('./app/models/carousel/carousel_salvar.php', {
            method: 'POST',
            body: formData
        })
            .then(async response => {
                let data;

                try {
                    data = await response.json();
                } catch (e) {
                    throw new Error('A resposta do servidor não está em JSON.');
                }

                if (!response.ok) {
                    throw new Error(data.mensagem || 'Erro ao salvar as imagens.');
                }

                return data;
            })
            .then(data => {
                mostrarMensagem(data.mensagem || 'Imagens salvas com sucesso!', 'success');

                form.reset();
                previewGrid.innerHTML = '';
                previewContainer.classList.add('d-none');

                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            })
            .catch(error => {
                mostrarMensagem(error.message || 'Erro ao salvar as imagens.', 'danger');
            })
            .finally(() => {
                btnSalvar.disabled = false;
                btnSalvar.innerHTML = '<i class="fa fa-save me-2"></i>Salvar imagens';
            });
    });
});