document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('formEditarProduto');
    const btnSubmit = document.getElementById('btnEditarProduto');

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
            btnSubmit.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Atualizando...';
        } else {
            btnSubmit.disabled = false;
            btnSubmit.innerHTML = btnSubmit.dataset.textoOriginal || 'Atualizar produto';
        }
    }

    form.addEventListener('submit', async function (e) {
        e.preventDefault();

        const formData = new FormData(form);

        setLoading(true);

        try {
            const response = await fetch('./app/models/produto/produto_editar.php', {
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
                throw new Error(data?.mensagem || 'Erro ao atualizar produto.');
            }

            await Swal.fire({
                icon: 'success',
                title: 'Produto atualizado',
                text: data.mensagem || 'Produto atualizado com sucesso.'
            });

            window.location.href = 'admin.php?page=produto&acao=' + encodeURIComponent('todos produtos');

        } catch (error) {
            mostrarMensagem(error.message || 'Erro inesperado.');
        } finally {
            setLoading(false);
        }
    });
});