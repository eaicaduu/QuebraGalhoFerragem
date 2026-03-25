document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('formImportacao');
    const inputFile = document.getElementById('arquivo_importacao');
    const mensagem = document.getElementById('mensagemImportacao');
    const btn = document.getElementById('btnImportar');

    if (!form || !inputFile || !mensagem || !btn) {
        return;
    }

    const TAMANHO_MAXIMO_MB = 20;

    function limparMensagem() {
        mensagem.innerHTML = '';
    }

    function mostrarMensagem(texto, tipo = 'danger') {
        mensagem.innerHTML = `
            <div class="alert alert-${tipo} alert-dismissible fade show" role="alert">
                ${texto}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
            </div>
        `;
    }

    function mostrarResultado(qtdLinhas, nomeArquivo) {
        mensagem.innerHTML = `
            <div class="alert alert-success shadow-sm border-0 mb-0" role="alert">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    <div>
                        <div class="fw-semibold mb-1">Arquivo processado com sucesso</div>
                        <div>
                            <strong>Arquivo:</strong> ${nomeArquivo}
                        </div>
                        <div>
                            <strong>Quantidade de linhas encontradas:</strong> ${qtdLinhas}
                        </div>
                    </div>

                    <div>
                        <button type="button" class="btn btn-outline-primary" id="btnVisualizarImportacao">
                            <i class="fa fa-eye me-2"></i>Visualizar
                        </button>
                    </div>
                </div>
            </div>
        `;

        const btnVisualizar = document.getElementById('btnVisualizarImportacao');
        if (btnVisualizar) {
            btnVisualizar.addEventListener('click', function () {
                if (window.resultadoImportacao && window.resultadoImportacao.visualizar_url) {
                    window.location.href = window.resultadoImportacao.visualizar_url;
                } else {
                    mostrarMensagem('Não foi possível abrir a visualização.', 'warning');
                }
            });
        }
    }

    function validarArquivo(file) {
        if (!file) {
            return 'Selecione um arquivo para importar.';
        }

        const nome = file.name || '';
        const partes = nome.split('.');
        const extensao = partes.length > 1 ? partes.pop().toLowerCase() : '';

        if (!['pdf', 'txt'].includes(extensao)) {
            return 'Formato inválido. Envie apenas arquivos PDF ou TXT.';
        }

        const tamanhoMaximoBytes = TAMANHO_MAXIMO_MB * 1024 * 1024;
        if (file.size > tamanhoMaximoBytes) {
            return `O arquivo excede o limite de ${TAMANHO_MAXIMO_MB} MB.`;
        }

        return null;
    }

    function setLoading(ativo) {
        if (ativo) {
            btn.disabled = true;
            btn.dataset.textoOriginal = btn.innerHTML;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Enviando...';
            inputFile.disabled = true;
        } else {
            btn.disabled = false;
            btn.innerHTML = btn.dataset.textoOriginal || '<i class="fa fa-upload me-2"></i>Enviar arquivo';
            inputFile.disabled = false;
        }
    }

    form.addEventListener('submit', async function (e) {
        e.preventDefault();
        limparMensagem();

        const file = inputFile.files[0];
        const erroValidacao = validarArquivo(file);

        if (erroValidacao) {
            mostrarMensagem(erroValidacao, 'danger');
            return;
        }

        const formData = new FormData();
        formData.append('arquivo_importacao', file);

        setLoading(true);

        try {
            const response = await fetch('./app/models/importar/importar_upload.php', {
                method: 'POST',
                body: formData
            });

            let data = null;

            try {
                data = await response.json();
            } catch (jsonError) {
                throw new Error('O servidor retornou uma resposta inválida.');
            }

            if (!response.ok) {
                throw new Error(data?.mensagem || 'Erro ao enviar o arquivo.');
            }

            if (!data || data.status !== 'success') {
                throw new Error(data?.mensagem || 'Não foi possível processar o arquivo.');
            }

            window.resultadoImportacao = data;

            window.resultadoImportacao = data;

            await Swal.fire({
                icon: 'success',
                title: 'Arquivo processado!',
                html: `
                    <div class="text-center">
                        <div>Produtos encontrados: ${data.quantidade_linhas ?? 0}</div>
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Visualizar',
                cancelButtonText: 'Fechar',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    if (data.visualizar_url) {
                        window.location.href = data.visualizar_url;
                    } else {
                        mostrarMensagem('Não foi possível abrir a visualização.', 'warning');
                    }
                }
            });

            form.reset();

        } catch (error) {
            mostrarMensagem(error.message || 'Ocorreu um erro inesperado ao importar.', 'danger');
        } finally {
            setLoading(false);
        }
    });
});