document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('formNovoProduto');
    const btnSubmit = form ? form.querySelector('button[type="submit"]') : null;

    if (!form || !btnSubmit) return;

    function mostrarMensagem(texto, tipo = 'error') {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: tipo === 'success' ? 'success' : 'error',
                title: tipo === 'success' ? 'Sucesso' : 'Erro',
                text: texto
            });
        } else {
            alert(texto);
        }
    }

    function setLoading(ativo) {
        if (ativo) {
            btnSubmit.disabled = true;
            btnSubmit.dataset.textoOriginal = btnSubmit.innerHTML;
            btnSubmit.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Salvando...';
        } else {
            btnSubmit.disabled = false;
            btnSubmit.innerHTML = btnSubmit.dataset.textoOriginal || 'Salvar produto';
        }
    }

    form.addEventListener('submit', async function (e) {
        e.preventDefault();

        const formData = new FormData(form);

        setLoading(true);

        try {
            const response = await fetch('./app/models/produto/produto_novo.php', {
                method: 'POST',
                body: formData
            });

            let data = null;

            try {
                data = await response.json();
            } catch (e) {
                throw new Error('Resposta inválida do servidor.');
            }

            if (!response.ok || !data || data.status !== true) {
                throw new Error(data?.mensagem || 'Erro ao salvar produto.');
            }

            if (typeof Swal !== 'undefined') {
                await Swal.fire({
                    icon: 'success',
                    title: 'Produto salvo',
                    text: data.mensagem || 'Salvo com sucesso'
                });
            }

            form.reset();

            const preview = document.getElementById('previewImagem');
            const container = document.getElementById('previewContainer');

            if (preview && container) {
                preview.src = '';
                container.style.display = 'none';
            }

        } catch (error) {
            mostrarMensagem(error.message || 'Erro inesperado.');
        } finally {
            setLoading(false);
        }
    });
});