window.excluirImagemCarousel = async function (item, id, limparSelecao) {
    const result = await Swal.fire({
        icon: 'warning',
        title: 'Excluir imagem?',
        text: 'Essa ação não pode ser desfeita.',
        showCancelButton: true,
        confirmButtonText: 'Excluir',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#dc3545'
    });

    if (!result.isConfirmed) return;

    try {
        const response = await fetch('./app/models/carousel/carousel_deleta.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id })
        });

        const data = await response.json();

        if (!response.ok || !data.status) {
            throw new Error(data?.mensagem || 'Erro ao excluir imagem.');
        }

        await Swal.fire({
            icon: 'success',
            title: 'Excluída',
            text: data.mensagem || 'Imagem removida com sucesso.'
        });

        const card = item.closest('.col-6, .col-md-4, .col-lg-3');
        if (card) {
            card.remove();
        }

        const contador = document.getElementById('contadorCarousel');
        if (contador) {
            const totalAtual = document.querySelectorAll('[data-carousel-id]').length;
            contador.textContent = `${totalAtual} imagem(ns) no carousel`;
        }

        if (typeof limparSelecao === 'function') {
            limparSelecao();
        }

    } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Erro',
            text: error.message || 'Não foi possível excluir a imagem.'
        });
    }
};