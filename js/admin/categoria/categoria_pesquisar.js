document.addEventListener('DOMContentLoaded', function () {

    const input = document.getElementById('inputCategoria');
    const lista = document.getElementById('listaCategorias');
    const hidden = document.getElementById('categoria_id');

    if (!input || !lista || !hidden) return;

    input.addEventListener('focus', () => {
        lista.style.display = 'block';
    });

    input.addEventListener('input', function () {
        const termo = this.value.toLowerCase();

        document.querySelectorAll('.item-categoria-select').forEach(item => {
            const nome = item.dataset.nome.toLowerCase();

            item.style.display = nome.includes(termo) ? '' : 'none';
        });
    });

    document.querySelectorAll('.item-categoria-select').forEach(item => {
        item.addEventListener('click', function () {
            input.value = this.dataset.nome;
            hidden.value = this.dataset.id;

            lista.style.display = 'none';
        });
    });

    document.addEventListener('click', function (e) {
        if (!e.target.closest('.position-relative')) {
            lista.style.display = 'none';
        }
    });

});